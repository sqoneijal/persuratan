import lozad from "lozad";
import React, { useLayoutEffect } from "react";
import { Col, Container, Row } from "react-bootstrap";

const Home = () => {
   useLayoutEffect(() => {
      lozad().observe();
      return () => {};
   }, []);

   return (
      <section className="banner-7 bg_img oh bottom_right lozad" data-background-image="/assets/banner/banner-bg-7.webp">
         <Container>
            <Row className="align-items-center">
               <Col lg={6}>
                  <div className="banner-content-7">
                     <h1 className="title">UINAR PERSURATAN</h1>
                     <p>Solusi cepat untuk segala kebutuhan surat akademik: aktif kuliah, cuti, magang, hingga pernyataan beasiswa.</p>
                  </div>
               </Col>
               <Col lg={6} className="d-lg-block d-none">
                  <img data-src="/assets/banner/banner-7.webp" alt="banner" className="lozad" />
               </Col>
               <Col xs={12}>
                  <div className="counter-wrapper-3">
                     <div className="counter-item">
                        <div className="counter-thumb">
                           <img data-src="/assets/banner/counter1.webp" alt="icon" className="lozad" />
                        </div>
                        <div className="counter-content">
                           <h2 className="title">
                              <span className="counter">17501</span>
                           </h2>
                           <span className="name">Pernyataan Masih Kuliah</span>
                        </div>
                     </div>
                     <div className="counter-item">
                        <div className="counter-thumb">
                           <img data-src="/assets/banner/counter1.webp" alt="icon" className="lozad" />
                        </div>
                        <div className="counter-content">
                           <h2 className="title">
                              <span className="counter">17501</span>
                           </h2>
                           <span className="name">Pernyataan Tidak Menerima Beasiswa</span>
                        </div>
                     </div>
                     <div className="counter-item">
                        <div className="counter-thumb">
                           <img data-src="/assets/banner/counter2.webp" alt="icon" className="lozad" />
                        </div>
                        <div className="counter-content">
                           <h2 className="title">
                              <span className="counter">1987</span>
                           </h2>
                           <span className="name">Visitors</span>
                        </div>
                     </div>
                  </div>
               </Col>
            </Row>
         </Container>
      </section>
   );
};
export default Home;
