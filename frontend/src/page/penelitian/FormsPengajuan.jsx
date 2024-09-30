import React, { useState } from "react";
import { Col, Container, Form, Row } from "react-bootstrap";
import { useDispatch, useSelector } from "react-redux";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const FormsPengajuan = ({ setApakahSudahMengajukan }) => {
   const { module, init } = useSelector((e) => e.redux);
   const { periode, biodata } = module;
   const dispatch = useDispatch();

   // bool
   const [isSubmit, setIsSubmit] = useState(false);

   // bool
   const [input, setInput] = useState({});
   const [errors, setErrors] = useState({});

   const submit = (e) => {
      e.preventDefault();
      const formData = {
         nim: h.parse("preferred_username", init),
         periode: h.parse("nama_singkat", periode),
         id_prodi: h.parse("id_program_studi", biodata),
      };
      Object.keys(input).forEach((key) => (formData[key] = input[key]));

      setIsSubmit(true);
      const fetch = h.post(`/akademik/penelitian/submit`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         setErrors(data.errors);
         h.notification(data.status, data.message);

         if (!data.status) return;

         dispatch(setModule({ ...module, detailContent: data.data }));
         setApakahSudahMengajukan(true);
      });
      fetch.finally(() => {
         setIsSubmit(false);
      });
   };

   return (
      <section className="contact-section padding-top padding-bottom">
         <Container>
            <Row className="justify-content-center justify-content-lg-between">
               <Col lg={12}>
                  <div className="contact-wrapper">
                     <h4 className="title text-center mb-30">Ajukan permohonan surat penelitian</h4>
                     <Form onSubmit={isSubmit ? null : submit} className="contact-form">
                        {h.form_text(
                           `Lokasi Tujuan Surat Penelitian`,
                           `surat_kepada`,
                           {
                              onChange: (e) => setInput((prev) => ({ ...prev, [e.target.name]: e.target.value })),
                              value: h.parse(`surat_kepada`, input),
                           },
                           true,
                           errors
                        )}
                        {h.form_text(
                           `Judul Penelitian`,
                           `judul_penelitian`,
                           {
                              onChange: (e) => setInput((prev) => ({ ...prev, [e.target.name]: e.target.value })),
                              value: h.parse(`judul_penelitian`, input),
                           },
                           true,
                           errors
                        )}
                        <div className="form-group">
                           <input type="submit" value={isSubmit ? "Loading..." : "Daftar"} disabled={isSubmit} />
                        </div>
                     </Form>
                  </div>
               </Col>
            </Row>
         </Container>
      </section>
   );
};
export default FormsPengajuan;
