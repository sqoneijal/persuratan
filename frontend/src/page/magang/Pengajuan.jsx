import React, { useState } from "react";
import { Col, Container, Form, Row } from "react-bootstrap";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const Pengajuan = () => {
   const { module } = useSelector((e) => e.redux);
   const { periode, biodata } = module;

   // bool
   const [isSubmit, setIsSubmit] = useState(false);

   // object
   const [input, setInput] = useState({});
   const [errors, setErrors] = useState({});

   const submit = (e) => {
      e.preventDefault();
      const formData = {
         nim: h.parse("nim", biodata),
         periode: h.parse("nama_singkat", periode),
         nama: h.parse("nama", biodata),
         id_prodi: h.parse("id_program_studi", biodata),
      };
      Object.keys(input).forEach((key) => (formData[key] = input[key]));

      setIsSubmit(true);
      const fetch = h.post(`/akademik/magang/submit`, formData);
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

         window.location.reload();
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
                     <h4 className="title text-center mb-30">Ajukan permohonan surat magang</h4>
                     <Form onSubmit={isSubmit ? null : submit} className="contact-form">
                        {h.form_text(
                           `Lokasi Magang`,
                           `lokasi_magang`,
                           {
                              onChange: (e) => setInput((prev) => ({ ...prev, [e.target.name]: e.target.value })),
                              value: h.parse(`lokasi_magang`, input),
                           },
                           true,
                           errors
                        )}
                        {h.form_text(
                           `Tujuan Surat`,
                           `tujuan_surat`,
                           {
                              onChange: (e) => setInput((prev) => ({ ...prev, [e.target.name]: e.target.value })),
                              value: h.parse(`tujuan_surat`, input),
                           },
                           true,
                           errors
                        )}
                        {h.form_text(
                           `Nomor HP Yang Aktif`,
                           `no_kontak`,
                           {
                              onChange: (e) => setInput((prev) => ({ ...prev, [e.target.name]: e.target.value })),
                              value: h.parse(`no_kontak`, input),
                           },
                           true,
                           errors
                        )}
                        {h.form_text(
                           `Alamat Tinggal Sekarang`,
                           `alamat`,
                           {
                              onChange: (e) => setInput((prev) => ({ ...prev, [e.target.name]: e.target.value })),
                              value: h.parse(`alamat`, input),
                           },
                           true,
                           errors
                        )}
                        <div className="form-group">
                           <input type="submit" value={isSubmit ? "Loading..." : "Ajukan"} disabled={isSubmit} />
                        </div>
                     </Form>
                  </div>
               </Col>
            </Row>
         </Container>
      </section>
   );
};
export default Pengajuan;
