import React, { useState } from "react";
import { Col, Container, Row } from "react-bootstrap";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const FormsPengajuan = () => {
   const { module } = useSelector((e) => e.redux);
   const { periode, biodata } = module;

   // bool
   const [isSubmit, setIsSubmit] = useState(false);

   const submit = (e) => {
      e.preventDefault();
      const formData = {
         nim: h.parse("nim", biodata),
         periode: h.parse("nama_singkat", periode),
         id_prodi: h.parse("id_program_studi", biodata),
      };

      setIsSubmit(true);
      const fetch = h.post(`/akademik/tidakmenerimabeasiswa/submit`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         h.notification(data.status, data.message);

         if (!data.status) return;

         window.location.reload();
      });
      fetch.finally(() => {
         setIsSubmit(false);
      });
   };

   return (
      <section className="blog-single-section padding-bottom" style={{ marginTop: "unset" }}>
         <Container>
            <Row className="justify-content-center">
               <Col lg={10}>
                  <article>
                     <div className="post-details">
                        <div className="post-inner">
                           <div className="post-header">
                              <h3 className="title">Pengajuan</h3>
                           </div>
                           <div className="post-content">
                              <p>Apakah anda ingin mengajukan permohonan pernyataan tidak menerima beasiswa?</p>
                           </div>
                        </div>
                        <div className="tags-area">
                           <div className="tags">
                              {h.buttons(`Ajukan Permohonan`, isSubmit, {
                                 onClick: isSubmit ? null : submit,
                              })}
                           </div>
                        </div>
                     </div>
                  </article>
               </Col>
            </Row>
         </Container>
      </section>
   );
};
export default FormsPengajuan;
