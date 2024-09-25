import React, { useLayoutEffect, useState } from "react";
import { Bars } from "react-loader-spinner";
import { useDispatch, useSelector } from "react-redux";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const Header = React.lazy(() => import("./Header"));
const StatusPengajuan = React.lazy(() => import("./StatusPengajuan"));
const FormsPengajuan = React.lazy(() => import("./FormsPengajuan"));

const Context = () => {
   const { module } = useSelector((e) => e.redux);
   const { periode, biodata, detailContent } = module;
   const dispatch = useDispatch();

   // bool
   const [isLoading, setIsLoading] = useState(true);

   const initPage = (nim, periode) => {
      const formData = { nim, periode };

      setIsLoading(true);
      const fetch = h.post(`/akademik/surataktifkuliah`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         if (data.status) {
            dispatch(setModule({ ...module, detailContent: data.data }));
         }
      });
      fetch.finally(() => {
         setIsLoading(false);
      });
   };

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

   useLayoutEffect(() => {
      if (h.objLength(periode) && h.objLength(biodata)) initPage(h.parse("nim", biodata), periode.nama_singkat);
      return () => {};
   }, [periode, biodata]);

   return isLoading ? (
      loader
   ) : (
      <React.Suspense fallback={loader}>
         <Header />
         {h.objLength(detailContent) ? <StatusPengajuan /> : <FormsPengajuan />}
         {/* <section className="padding-top padding-bottom">
            <Container>
               <Row className="justify-content-between">
                  <Col lg={12} xl={12}>
                     {h.objLength(detailContent) ? <StatusPengajuan /> : <FormsPengajuan />}
                  </Col>
               </Row>
            </Container>
         </section> */}
      </React.Suspense>
   );
};
export default Context;
