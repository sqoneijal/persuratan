import sign from "jwt-encode";
import React, { useLayoutEffect, useState } from "react";
import { Col, Container, Row, Table } from "react-bootstrap";
import { useSelector } from "react-redux";
import { Each } from "~/src/Each";
import * as h from "~/src/Helpers";

const Lists = () => {
   const { module } = useSelector((e) => e.redux);
   const { daftarTranskrip, biodata } = module;

   // string
   const [total_sks, setTotal_sks] = useState(0);
   const [total_bobot, setTotal_bobot] = useState(0);

   useLayoutEffect(() => {
      let sks = 0;
      let bobot = 0;
      daftarTranskrip.forEach((row) => {
         sks += h.toInt(row.sks_mata_kuliah);
         bobot += h.toInt(row.sks_mata_kuliah) * h.toInt(row.nilai_angka);
      });

      setTotal_sks(sks);
      setTotal_bobot(bobot);
      return () => {};
   }, [daftarTranskrip]);

   const jwt_key = "KQYsG4Hi201ajyEzOSGzr4MVfw==";
   const jwt_payload = {
      nim: h.parse("nim", biodata),
   };
   const jwt_encode = sign(jwt_payload, jwt_key);

   return (
      <section className="privacy-section padding-top padding-bottom">
         <Container>
            <Row className="justify-content-between">
               <Col lg={12} xl={12}>
                  <article className="mt-70 mt-lg-0">
                     <div className="privacy-item">
                        <Table responsive>
                           <thead>
                              <tr>
                                 <th className="text-center">NO</th>
                                 <th className="text-center">KODE</th>
                                 <th className="text-center">NAMA MATAKULIAH</th>
                                 <th className="text-center">SKS</th>
                                 <th className="text-center">NILAI HURUF</th>
                                 <th className="text-center">TOTAL BOBOT</th>
                              </tr>
                           </thead>
                           <tbody>
                              <Each
                                 of={daftarTranskrip}
                                 render={(row, index) => (
                                    <tr>
                                       <td className="text-center">{index + 1}</td>
                                       <td className="text-center">{h.parse("kode_mata_kuliah", row)}</td>
                                       <td>{h.parse("nama_mata_kuliah", row)}</td>
                                       <td className="text-center">{h.parse("sks_mata_kuliah", row)}</td>
                                       <td className="text-center">{h.parse("nilai_huruf", row)}</td>
                                       <td className="text-center">
                                          {h.toInt(h.parse("nilai_angka", row)) * h.toInt(h.parse("sks_mata_kuliah", row))}
                                       </td>
                                    </tr>
                                 )}
                              />
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th colSpan={5} className="text-end">
                                    TOTAL SATUAN KREDIT SEMESTER (SKS)
                                 </th>
                                 <th className="text-center">{total_sks}</th>
                              </tr>
                              <tr>
                                 <th colSpan={5} className="text-end">
                                    TOTAL BOBOT
                                 </th>
                                 <th className="text-center">{total_bobot}</th>
                              </tr>
                              <tr>
                                 <th colSpan={5} className="text-end">
                                    INDEKS PRESTASI KUMULATIF
                                 </th>
                                 <th className="text-center">{total_bobot > 0 ? (total_bobot / total_sks).toFixed(2) : 0}</th>
                              </tr>
                           </tfoot>
                        </Table>
                        <a href={`${window.apiPath}/akademik/transkrip/cetak/${jwt_encode}`} className="button-4" target="_blank">
                           Download PDF
                        </a>
                     </div>
                  </article>
               </Col>
            </Row>
         </Container>
      </section>
   );
};
export default Lists;
