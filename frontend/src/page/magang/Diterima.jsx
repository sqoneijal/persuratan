import React from "react";
import { Col, Container, Row } from "react-bootstrap";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const Diterima = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   return (
      <section className="blog-single-section padding-bottom">
         <Container>
            <Row className="justify-content-center">
               <Col lg={10}>
                  <article>
                     <div className="post-details">
                        <div className="post-inner">
                           <div className="post-header">
                              <h3 className="title">Pengajuan Surat Magang Diterima</h3>
                           </div>
                           <div className="post-content">
                              <table style={{ width: "100%" }}>
                                 <tbody>
                                    <tr>
                                       <td>Periode</td>
                                    </tr>
                                    <tr>
                                       <td className="fw-bold">
                                          {h.semester(`${h.parse("tahun_ajaran", detailContent)}${h.parse("id_semester", detailContent)}`)}
                                       </td>
                                    </tr>
                                    <tr>
                                       <td>Nomor Surat</td>
                                    </tr>
                                    <tr>
                                       <td className="fw-bold">{h.parse("no_surat", detailContent)}</td>
                                    </tr>
                                    <tr>
                                       <td>Tujuan Surat</td>
                                    </tr>
                                    <tr>
                                       <td className="fw-bold">{h.parse("tujuan_surat", detailContent)}</td>
                                    </tr>
                                    <tr>
                                       <td>Lokasi Magang</td>
                                    </tr>
                                    <tr>
                                       <td className="fw-bold">{h.parse("lokasi_magang", detailContent)}</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        <div className="tags-area">
                           <div className="tags">
                              {h.buttons(`Download PDF`, false, {
                                 onClick: () => window.open(`${window.apiPath}/akademik/magang/cetak/${h.parse("id", detailContent)}`, "_blank"),
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
export default Diterima;
