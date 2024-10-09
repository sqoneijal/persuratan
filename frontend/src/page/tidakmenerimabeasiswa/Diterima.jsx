import moment from "moment";
import React from "react";
import { Col, Container, Row } from "react-bootstrap";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const Diterima = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   return (
      <section className="blog-single-section padding-bottom" style={{ marginTop: "unset" }}>
         <Container>
            <Row className="justify-content-center">
               <Col lg={10}>
                  <article>
                     <div className="post-details">
                        <div className="post-inner">
                           <div className="post-header">
                              <h3 className="title">Disetujui</h3>
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
                                       <td>Berlaku Sampai</td>
                                    </tr>
                                    <tr>
                                       <td className="fw-bold">{moment(h.parse("berlaku_sampai", detailContent)).format("DD MMMM YYYY")}</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        <div className="tags-area">
                           <div className="tags">
                              {h.buttons(`Download PDF`, false, {
                                 onClick: () =>
                                    window.open(`${window.apiPath}/akademik/tidakmenerimabeasiswa/cetak/${h.parse("id", detailContent)}`, "_blank"),
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
