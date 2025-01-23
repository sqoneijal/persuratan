import sign from "jwt-encode";
import React, { useLayoutEffect, useState } from "react";
import { Accordion, Col, Container, Row, Table, useAccordionButton } from "react-bootstrap";
import { useDispatch, useSelector } from "react-redux";
import { Each } from "~/src/Each";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const CustomHeaderToggle = ({ children, eventKey }) => {
   const { module } = useSelector((e) => e.redux);
   const dispatch = useDispatch();

   const decoratedOnClick = useAccordionButton(eventKey, () => {
      dispatch(setModule({ ...module, detailNavActive: eventKey }));
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
   const { detailContent, detailNavActive, biodata } = module;
   const { periode, daftar_matkul } = detailContent;

   // string
   const [total_sks, setTotal_sks] = useState(0);
   const [total_bobot, setTotal_bobot] = useState(0);

   useLayoutEffect(() => {
      if (detailNavActive && daftar_matkul[detailNavActive]) {
         let total = 0;
         let bobot = 0;
         daftar_matkul[detailNavActive]
            .filter((e) => e.is_nilai_akhir === "1")
            .forEach((row) => {
               total += h.toInt(row.sks);
               bobot += h.toInt(row.sks) * h.toInt(row.nilai_angka);
            });
         setTotal_sks(total);
         setTotal_bobot(bobot);
      }
      return () => {};
   }, [detailNavActive, daftar_matkul]);

   const jwt_key = "KQYsG4Hi201ajyEzOSGzr4MVfw==";
   const jwt_payload = {
      nim: h.parse("nim", biodata),
      periode: detailNavActive,
   };
   const jwt_encode = sign(jwt_payload, jwt_key);

   return (
      <section className="faq-section padding-top padding-bottom">
         <Container>
            <Row className="justify-content-between">
               <Col lg={12} xl={12}>
                  <Accordion as={"article"} bsPrefix="mt-70 mt-lg-0">
                     <div className="faq--wrapper">
                        <div className="faq--area">
                           <Each
                              of={periode}
                              render={(row) => (
                                 <Accordion.Item bsPrefix={`faq--item ${detailNavActive === row ? "open" : ""}`}>
                                    <CustomHeaderToggle eventKey={row}>Periode {h.semester(row)}</CustomHeaderToggle>
                                    <Accordion.Collapse
                                       bsPrefix="faq-content"
                                       eventKey={row}
                                       style={{ display: detailNavActive == row ? "block" : "none" }}>
                                       <React.Fragment>
                                          <Table responsive>
                                             <thead>
                                                <tr>
                                                   <th className="text-center align-middle" rowSpan={2}>
                                                      NO
                                                   </th>
                                                   <th className="text-center align-middle" rowSpan={2}>
                                                      KODE
                                                   </th>
                                                   <th className="align-middle" rowSpan={2}>
                                                      NAMA MATAKULIAH
                                                   </th>
                                                   <th className="text-center align-middle" rowSpan={2}>
                                                      SKS
                                                   </th>
                                                   <th className="text-center align-middle" colSpan={2}>
                                                      NILAI HURUF
                                                   </th>
                                                   <th className="text-center align-middle" rowSpan={2}>
                                                      TOTAL BOBOT
                                                   </th>
                                                </tr>
                                                <tr>
                                                   <th className="text-center align-middle">HURUF</th>
                                                   <th className="text-center align-middle">BOBOT</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                                <Each
                                                   of={daftar_matkul[row].filter((e) => e.is_nilai_akhir === "1")}
                                                   render={(row, index) => (
                                                      <tr>
                                                         <td className="text-center">{index + 1}</td>
                                                         <td className="text-center">{h.parse("kode_mata_kuliah", row)}</td>
                                                         <td>{h.parse("mata_kuliah", row)}</td>
                                                         <td className="text-center">{h.parse("sks", row)}</td>
                                                         <td className="text-center">{h.parse("nilai_huruf", row)}</td>
                                                         <td className="text-center">{h.parse("nilai_angka", row)}</td>
                                                         <td className="text-center">{h.toInt(row.sks) * h.toInt(row.nilai_angka)}</td>
                                                      </tr>
                                                   )}
                                                />
                                             </tbody>
                                             <tfoot>
                                                <tr>
                                                   <th colSpan={3} className="text-end">
                                                      JUMLAH
                                                   </th>
                                                   <th className="text-center">{total_sks}</th>
                                                   <td colSpan={2} />
                                                   <th className="text-center">{total_bobot}</th>
                                                </tr>
                                                <tr>
                                                   <th colSpan={3} className="text-end">
                                                      IPS
                                                   </th>
                                                   <th className="text-center">{total_bobot > 0 ? (total_bobot / total_sks).toFixed(2) : 0}</th>
                                                   <td colSpan={3} />
                                                </tr>
                                             </tfoot>
                                          </Table>
                                          <a href={`${window.apiPath}/akademik/khs/cetak/${jwt_encode}`} className="button-4" target="_blank">
                                             Download PDF
                                          </a>
                                       </React.Fragment>
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
   );
};
export default Lists;
