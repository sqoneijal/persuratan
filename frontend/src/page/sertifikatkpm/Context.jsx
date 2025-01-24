import React, { useLayoutEffect, useState } from "react";
import { Col, Container, Row } from "react-bootstrap";
import { Bars } from "react-loader-spinner";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const Header = React.lazy(() => import("./Header"));

const Context = () => {
   const { init } = useSelector((e) => e.redux);

   const [state, setState] = useState({
      isLoading: true,
      dataSertifikat: {},
   });

   const getData = (nim) => {
      const formData = { nim };

      const fetch = h.post(`/sertifikatkpm/getdata`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         setState((prev) => ({ ...prev, dataSertifikat: data }));
      });
      fetch.finally(() => {
         setState((prev) => ({ ...prev, isLoading: false }));
      });
   };

   useLayoutEffect(() => {
      if (h.objLength(init)) getData(init.preferred_username);
      return () => {};
   }, [init]);

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
         wrapperClass="page-loader flex-column justify-content-center"
      />
   );

   return state.isLoading ? (
      loader
   ) : (
      <React.Suspense fallback={loader}>
         <Header />
         <section className="blog-single-section padding-bottom" style={{ marginTop: "unset" }}>
            <Container>
               <Row className="justify-content-center">
                  <Col lg={10}>
                     <article>
                        <div className="post-details">
                           <div className="post-inner">
                              <div className="post-content">
                                 {h.objLength(state.dataSertifikat) ? (
                                    h.buttons(`Download Sertifikat`, false, {
                                       onClick: () => window.open(`${window.apiPath}/sertifikatkpm/cetak/${state.dataSertifikat.id}`, "_blank"),
                                    })
                                 ) : (
                                    <p>Anda belum memiliki sertifikat KPM!</p>
                                 )}
                              </div>
                           </div>
                        </div>
                     </article>
                  </Col>
               </Row>
            </Container>
         </section>
      </React.Suspense>
   );
};
export default Context;
