import lozad from "lozad";
import moment from "moment";
import React, { useLayoutEffect } from "react";
import { Col, Container, Row } from "react-bootstrap";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const Home = () => {
   const { init } = useSelector((e) => e.redux);

   useLayoutEffect(() => {
      lozad().observe();
      return () => {};
   }, []);

   return (
      <React.Fragment>
         <section className="banner-7 bg_img oh bottom_right lozad" data-background-image="/assets/banner/banner-bg-7.webp">
            <Container>
               <Row className="align-items-center">
                  <Col lg={6}>
                     <div className="banner-content-7">
                        <h1 className="title">UINAR PERSURATAN</h1>
                        <p>Solusi cepat untuk segala kebutuhan surat akademik: aktif kuliah, cuti, magang, hingga pernyataan beasiswa.</p>
                        <div className="banner-button-group">
                           {h.objLength(init) ? (
                              <a
                                 href={`https://keycloak.ar-raniry.ac.id/auth/realms/uinar/protocol/openid-connect/logout?redirect_uri=${encodeURIComponent(
                                    location.href
                                 )}`}
                                 className="button-4">
                                 Logout
                              </a>
                           ) : (
                              <a
                                 href={`https://keycloak.ar-raniry.ac.id/auth/realms/uinar/protocol/openid-connect/auth?client_id=mael&redirect_uri=${encodeURIComponent(
                                    location.href
                                 )}&response_mode=fragment&response_type=code&scope=openid`}
                                 className="button-4">
                                 Login SSO
                              </a>
                           )}
                        </div>
                     </div>
                  </Col>
                  <Col lg={6} className="d-lg-block d-none">
                     <img data-src="/assets/banner/banner-7.webp" alt="banner" className="lozad" />
                  </Col>
               </Row>
            </Container>
         </section>
         <section className="work-section padding-bottom bg_img mb-md-95 pb-md-0 lozad" data-background-image="./assets/banner/work-bg.webp" id="how">
            <div className="oh padding-top pos-rel">
               <div className="top-shape d-none d-lg-block">
                  <img data-src="./assets/banner/work-shape.webp" alt="css" className="lozad" />
               </div>
               <div className="container">
                  <div className="row">
                     <div className="col-lg-8 col-xl-7">
                        <div className="section-header left-style cl-white">
                           <h2 className="title">Khusus Angkatan {moment().format("YYYY")}</h2>
                        </div>
                     </div>
                  </div>
                  <div className="work-slider owl-carousel owl-theme owl-loaded">
                     <div className="owl-stage-outer">
                        <div className="owl-stage" style={{ transition: "all", width: window.innerWidth, transform: "translate3d(0px, 0px, 0px)" }}>
                           <div className="owl-item active center" style={{ width: window.innerWidth / 1.5 }}>
                              <div className="work-item">
                                 <div className="work-thumb">
                                    <img data-src="./assets/banner/work1.webp" alt="work" className="lozad" />
                                 </div>
                                 <div className="work-content cl-white">
                                    <p>
                                       Jika anda belum pernah melakukan login dengan akun SSO UINAR, untuk default username dan password adalah{" "}
                                       <span className="text-danger fw-bold fs-3">NIM</span> dan tanggal lahir anda{" "}
                                       <span className="text-danger fw-bold fs-3">YYYYMMDD</span> (contoh penulisan tanggal lahir :{" "}
                                       {moment().format("YYYYMMDD")})
                                    </p>
                                    {h.objLength(init) ? (
                                       <a
                                          href={`https://keycloak.ar-raniry.ac.id/auth/realms/uinar/protocol/openid-connect/logout?redirect_uri=${encodeURIComponent(
                                             location.href
                                          )}`}
                                          className="get-button white light">
                                          Logout
                                       </a>
                                    ) : (
                                       <a
                                          href={`https://keycloak.ar-raniry.ac.id/auth/realms/uinar/protocol/openid-connect/auth?client_id=mael&redirect_uri=${encodeURIComponent(
                                             location.href
                                          )}&response_mode=fragment&response_type=code&scope=openid`}
                                          className="get-button white light">
                                          Login SSO <i className="flaticon-right" />
                                       </a>
                                    )}
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </React.Fragment>
   );
};
export default Home;
