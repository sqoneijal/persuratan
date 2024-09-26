import React, { useState } from "react";
import { Col, Container, Row } from "react-bootstrap";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const FormsPengajuan = ({ initPage }) => {
   const { init, module } = useSelector((e) => e.redux);
   const { periode } = module;

   // bool
   const [isSubmit, setIsSubmit] = useState(false);

   const submit = (e) => {
      e.preventDefault();
      const formData = { nim: h.parse("preferred_username", init), periode: h.parse("nama_singkat", periode) };

      setIsSubmit(true);
      const fetch = h.post(`/akademik/surataktifkuliah/pengajuan`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         h.notification(data.status, data.message);

         if (!data.status) return;

         initPage(h.parse("preferred_username", init), h.parse("nama_singkat", periode));
      });
      fetch.finally(() => {
         setIsSubmit(false);
      });
   };

   return (
      <section className="padding-top padding-bottom blog-single-section">
         <Container>
            <Row className="justify-content-center">
               <Col lg={8} xl={8}>
                  <article>
                     <div className="post-details">
                        <div className="post-inner">
                           <div className="post-header">
                              <h3 className="title">Apakah anda ingin mengajukan surat keterangan aktif kuliah!</h3>
                           </div>
                           <div className="tags-area">
                              {h.buttons(`Ajukan Surat Keterangan Aktif Kuliah`, isSubmit, {
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
