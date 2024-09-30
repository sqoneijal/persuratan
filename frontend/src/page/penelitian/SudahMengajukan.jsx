import lozad from "lozad";
import React, { useLayoutEffect } from "react";
import { Col, Container, Row } from "react-bootstrap";
import { Bars } from "react-loader-spinner";
import { useSelector } from "react-redux";
import Switch, { Case, Default } from "react-switch-case";

const Direview = React.lazy(() => import("./Direview"));
const Ditolak = React.lazy(() => import("./Ditolak"));
const Diterima = React.lazy(() => import("./Diterima"));

const SudahMengajukan = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   useLayoutEffect(() => {
      lozad().observe();
      return () => {};
   }, []);

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
         wrapperClass="page-loader flex-column bg-dark bg-opacity-25"
      />
   );

   return (
      <React.Suspense fallback={loader}>
         <section className="about-section padding-top padding-bottom oh">
            <Container>
               <Row className="align-items-center">
                  <Col xl={5}>
                     <div className="about-thumb rtl pr-xl-15">
                        <img src="/assets/banner/about.png" alt="about" className="lozad" />
                     </div>
                  </Col>
                  <Col xl={7} className="pl-xl-0">
                     <Switch condition={detailContent.is_approved}>
                        <Case value="f">
                           <Ditolak />
                        </Case>
                        <Case value="t">
                           <Diterima />
                        </Case>
                        <Default>
                           <Direview />
                        </Default>
                     </Switch>
                  </Col>
               </Row>
            </Container>
         </section>
      </React.Suspense>
   );
};
export default SudahMengajukan;
