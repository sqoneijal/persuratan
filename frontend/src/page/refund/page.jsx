import React, { lazy, useEffect, useState } from "react";
import { Col, Container, Form, Row } from "react-bootstrap";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const Header = lazy(() => import("./header"));

const Page = () => {
   const { init } = useSelector((e) => e.redux);

   const [isLoading, setIsLoading] = useState(true);
   const [input, setInput] = useState({});
   const [errors, setErrors] = useState({});
   const [isSubmit, setIsSubmit] = useState(false);

   const getData = (username) => {
      const formData = { username };

      const fetch = h.post(`/refund/getdata`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         setInput(data);
      });
      fetch.finally(() => {
         setIsLoading(false);
      });
   };

   useEffect(() => {
      if (Object.keys(init).length > 0) {
         getData(init?.preferred_username);
      }
      return () => {};
   }, [init]);

   const renderStatus = (status) => {
      if (status === "belum") {
         return "Silahkan lengkapi data dibawah ini untuk dapat dilakukan pengembalian dana.";
      }
   };

   const handleSubmit = (e) => {
      e.preventDefault();

      setIsSubmit(true);

      const formData = { ...input };

      const fetch = h.post(`/refund/submit`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         setErrors(data.errors);
      });
      fetch.finally(() => {
         setIsSubmit(false);
      });
   };

   return isLoading ? (
      <h5>🌀 Loading...</h5>
   ) : (
      <React.Suspense fallback={<h5>🌀 Loading...</h5>}>
         <Header />
         <section className="blog-single-section padding-bottom" style={{ marginTop: "unset" }}>
            <Container>
               <Row className="justify-content-center">
                  <Col lg={10}>
                     <article>
                        <div className="post-details">
                           <div className="post-inner">
                              <div className="post-header">
                                 <h6 className="title">{renderStatus(input?.status)}</h6>
                              </div>
                              <div className="post-content">
                                 {Object.keys(input).length > 0 ? (
                                    <Form className="contact-form" style={{ width: "100%" }}>
                                       {h.form_text(
                                          `Nominal Pengembalian`,
                                          `nominal_pengembalian`,
                                          {
                                             value: input?.nominal_pengembalian,
                                             disabled: true,
                                          },
                                          true,
                                          errors
                                       )}
                                       {h.form_text(
                                          `Nama Rekening Bank`,
                                          `nama_rekening`,
                                          {
                                             onChange: ({ target: { name, value } }) => setInput((prev) => ({ ...prev, [name]: value })),
                                             value: input?.nama_rekening,
                                          },
                                          true,
                                          errors
                                       )}
                                       {h.form_text(
                                          `Nomor Rekening Bank`,
                                          `nomor_rekening`,
                                          {
                                             onChange: ({ target: { name, value } }) => setInput((prev) => ({ ...prev, [name]: value })),
                                             value: input?.nomor_rekening,
                                          },
                                          true,
                                          errors
                                       )}
                                       {h.form_select(
                                          `Nama Bank`,
                                          `nama_bank_penerima`,
                                          {
                                             onChange: ({ target: { name, value } }) => setInput((prev) => ({ ...prev, [name]: value })),
                                             value: input?.nama_bank_penerima,
                                             options: [
                                                { value: "bas", label: "Bank Aceh (BAS)" },
                                                { value: "bsi", label: "Bank Syariah Indonesia (BSI)" },
                                             ],
                                          },
                                          true,
                                          errors
                                       )}
                                       {(input?.status === "belum" || input?.status === "gagal") &&
                                          h.buttons(`Submit`, isSubmit, {
                                             onClick: isSubmit ? null : handleSubmit,
                                          })}
                                    </Form>
                                 ) : (
                                    <p>Anda tidak memiliki data semester antara yang dapat dilakukan untuk melakukan pengembalian uang kembali!</p>
                                 )}
                              </div>
                           </div>
                        </div>
                     </article>
                  </Col>
               </Row>
            </Container>
         </section>
      </React.Suspense>
   );
};
export default Page;
