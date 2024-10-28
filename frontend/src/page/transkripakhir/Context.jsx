import React, { useLayoutEffect, useState } from "react";
import { Bars } from "react-loader-spinner";
import { useDispatch, useSelector } from "react-redux";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const Header = React.lazy(() => import("./Header"));
const Lists = React.lazy(() => import("./Lists"));

const Context = () => {
   const { module } = useSelector((e) => e.redux);
   const { biodata } = module;
   const dispatch = useDispatch();

   // bool
   const [isLoading, setIsLoading] = useState(true);

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

   const getData = (nim) => {
      const formData = { nim };

      setIsLoading(true);
      const fetch = h.post(`/sevima/transkrip`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         dispatch(setModule({ ...module, daftarTranskrip: data.data }));
      });
      fetch.finally(() => {
         setIsLoading(false);
      });
   };

   useLayoutEffect(() => {
      if (h.objLength(biodata)) getData(h.parse("nim", biodata));
      return () => {};
   }, [biodata]);

   return isLoading ? (
      loader
   ) : (
      <React.Suspense fallback={loader}>
         <Header />
         <Lists />
      </React.Suspense>
   );
};
export default Context;
