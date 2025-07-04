import React from "react";
import { Accordion, Col, Container, Row, useAccordionButton } from "react-bootstrap";
import { useDispatch, useSelector } from "react-redux";
import Switch, { Case, Default } from "react-switch-case";
import { Each } from "~/src/Each";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const FormsPengajuan = React.lazy(() => import("./FormsPengajuan"));

const CustomHeaderToggle = ({ children, eventKey }) => {
   const { module, init } = useSelector((e) => e.redux);
   const dispatch = useDispatch();

   const getStatusPengajuan = (periode, nim) => {
      const formData = { periode, nim };

      const fetch = h.post(`/akademik/surataktifkuliah/status`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         dispatch(
            setModule({
               ...module,
               detailNavActive: eventKey,
               detailContent: {
                  ...module.detailContent,
                  detailNavActive: eventKey,
                  detailSurat: data.status ? data.data : [],
               },
            })
         );
      });
   };

   const decoratedOnClick = useAccordionButton(eventKey, () => {
      getStatusPengajuan(eventKey, init.preferred_username);
   });

   return (
      <div className="faq-title" onClick={decoratedOnClick}>
         <h6 className="title">{children}</h6>
         <span className="icon" />
      </div>
   );
};

const Lists = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   const downloadPDF = () => {
      window.open(`${window.apiPath}/akademik/surataktifkuliah/cetak/${h.parse("id", detailContent.detailSurat)}`, "_blank");
   };

   return (
      typeof module.daftarPeriode !== "undefined" && (
         <section className="faq-section padding-top padding-bottom">
            <Container>
               <Row className="justify-content-between">
                  <Col>
                     <Accordion as={"article"} bsPrefix="mt-70 mt-lg-0">
                        <div className="faq--wrapper">
                           <div className="faq--area">
                              <Each
                                 of={module.daftarPeriode}
                                 render={(row) => (
                                    <Accordion.Item
                                       bsPrefix={`faq--item ${
                                          h.objLength(detailContent) && detailContent.detailNavActive === row.nama_singkat ? "open" : ""
                                       }`}>
                                       <CustomHeaderToggle eventKey={row.nama_singkat}>Periode {h.semester(row.nama_singkat)}</CustomHeaderToggle>
                                       <Accordion.Collapse
                                          bsPrefix="faq-content"
                                          eventKey={row.nama_singkat}
                                          style={{
                                             display:
                                                h.objLength(detailContent) && detailContent.detailNavActive === row.nama_singkat ? "block" : "none",
                                          }}>
                                          {h.objLength(detailContent) && h.objLength(detailContent.detailSurat) ? (
                                             <Switch condition={detailContent.detailSurat.status}>
                                                <Case value="1">
                                                   <div className="entry-content">
                                                      <p>Status pengajuan surat aktif kuliah Anda disetujui!</p>
                                                      {h.detail_label("Nomor Surat", h.parse("no_surat", detailContent.detailSurat))}
                                                      {h.detail_label("Tanggal Surat", h.parse("tgl_surat", detailContent.detailSurat, "date"))}
                                                   </div>

                                                   {h.buttons(`Download PDF`, false, {
                                                      onClick: () => downloadPDF(),
                                                   })}
                                                </Case>
                                                <Default>
                                                   <p>
                                                      Status pengajuan surat aktif kuliah Anda sedang direview oleh akademik fakultas. Untuk informasi
                                                      lebih lanjut dapat menghubungi pihak akademik fakultas Anda masing - masing!
                                                   </p>
                                                </Default>
                                             </Switch>
                                          ) : (
                                             <React.Suspense fallback={<div>Loading...</div>}>
                                                <FormsPengajuan />
                                             </React.Suspense>
                                          )}
                                       </Accordion.Collapse>
                                    </Accordion.Item>
                                 )}
                              />
                           </div>
                        </div>
                     </Accordion>
                  </Col>
               </Row>
            </Container>
         </section>
      )
   );
};
export default Lists;
