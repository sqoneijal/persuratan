import React, { useLayoutEffect, useState } from "react";
import { Bars } from "react-loader-spinner";
import { useDispatch, useSelector } from "react-redux";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const Header = React.lazy(() => import("./Header"));
const Lists = React.lazy(() => import("./Lists"));

const Context = () => {
   const { module, init } = useSelector((e) => e.redux);
   const dispatch = useDispatch();

   const [state, setState] = useState({
      isLoading: true,
   });

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

   const initPage = () => {
      const fetch = h.get(`/akademik/surataktifkuliah/initpage`);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;

         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         dispatch(setModule({ ...module, ...data }));
      });
      fetch.finally(() => {
         setState((prev) => ({ ...prev, isLoading: false }));
      });
   };

   useLayoutEffect(() => {
      if (h.objLength(module) && state.isLoading) initPage();
      return () => {};
   }, [module, state]);

   return state.isLoading
      ? loader
      : h.objLength(init) && (
           <React.Suspense fallback={loader}>
              <Header />
              <Lists />
           </React.Suspense>
        );
};
export default Context;
