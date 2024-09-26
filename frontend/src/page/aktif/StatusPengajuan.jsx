import React from "react";
import { Col, Container, Row } from "react-bootstrap";
import { useSelector } from "react-redux";
import Switch, { Case, Default } from "react-switch-case";
import * as h from "~/src/Helpers";

const FormsDiterima = React.lazy(() => import("./FormsDiterima"));

const StatusPengajuan = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   const downloadPDF = () => {
      window.open(`${window.apiPath}/akademik/surataktifkuliah/cetak/${h.parse("id", detailContent)}`, "_blank");
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
                              <h3 className="title">Status Pengajuan</h3>
                           </div>
                           <div className="post-content">
                              <Switch condition={detailContent.status}>
                                 <Case value="1">
                                    <div className="entry-content">
                                       <p>Status pengajuan surat aktif kuliah Anda disetujui!</p>
                                       <FormsDiterima />
                                    </div>
                                 </Case>
                                 <Default>
                                    <p>
                                       Status pengajuan surat aktif kuliah Anda sedang direview oleh akademik fakultas. Untuk informasi lebih lanjut
                                       dapat menghubungi pihak akademik fakultas Anda masing - masing!
                                    </p>
                                 </Default>
                              </Switch>
                           </div>
                           {detailContent.status === "1" && (
                              <div className="tags-area">
                                 {h.buttons(`Download PDF`, false, {
                                    onClick: () => downloadPDF(),
                                 })}
                              </div>
                           )}
                        </div>
                     </div>
                  </article>
               </Col>
            </Row>
         </Container>
      </section>
   );
};
export default StatusPengajuan;
