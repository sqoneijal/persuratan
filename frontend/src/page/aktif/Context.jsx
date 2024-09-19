import React from "react";
import { Col, Container, Row } from "react-bootstrap";
import { Bars } from "react-loader-spinner";

const Header = React.lazy(() => import("./Header"));

const Context = () => {
   const loader = (
      <Bars
         visible={true}
         color="#4fa94d"
         radius="9"
         wrapperStyle={{
            alignItems: "center",
            display: "flex",
            justifyContent: "center",
         }}
         wrapperclassName="page-loader flex-column bg-dark bg-opacity-25"
      />
   );

   return (
      <React.Suspense fallback={loader}>
         <Header />
         <section className="privacy-section padding-top padding-bottom">
            <Container>
               <Row className="justify-content-between">
                  <Col lg={12} xl={12}>
                     <div className="privacy-item">
                        <h3 className="title">What is Lorem Ipsum?</h3>
                        <p>
                           Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard
                           dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen
                           book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially
                           unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more
                           recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        </p>
                     </div>
                  </Col>
               </Row>
            </Container>
         </section>
      </React.Suspense>
   );
};
export default Context;
