import React from "react";
import { Col, Container, Row } from "react-bootstrap";

const Review = () => {
   return (
      <section className="blog-single-section padding-bottom" style={{ marginTop: "unset" }}>
         <Container>
            <Row className="justify-content-center">
               <Col lg={10}>
                  <article>
                     <div className="post-details">
                        <div className="post-inner">
                           <div className="post-header">
                              <h3 className="title">Pengajuan</h3>
                           </div>
                           <div className="post-content">
                              <p>Permohonan anda sedang di proses oleh akademk fakultas. Terima kasih!!!</p>
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
export default Review;
