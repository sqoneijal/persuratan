import React from "react";
import { Col, Container, Row } from "react-bootstrap";

const Review = () => {
   return (
      <section className="blog-single-section padding-bottom">
         <Container>
            <Row className="justify-content-center">
               <Col lg={10}>
                  <article>
                     <div className="post-details">
                        <div className="post-inner">
                           <div className="post-header">
                              <h3 className="title">Status Pengajuan Surat Magang</h3>
                           </div>
                           <div className="post-content">
                              <p>Pengajuan surat magang anda lakukan, sedang direview oleh akademik fakultas!</p>
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
