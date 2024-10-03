import React, { useLayoutEffect, useState } from "react";
import { Bars } from "react-loader-spinner";
import { useDispatch, useSelector } from "react-redux";
import Switch, { Case } from "react-switch-case";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const Pengajuan = React.lazy(() => import("./Pengajuan"));
const Header = React.lazy(() => import("./Header"));
const Review = React.lazy(() => import("./Review"));
const Diterima = React.lazy(() => import("./Diterima"));

const Context = () => {
   const { init, module } = useSelector((e) => e.redux);
   const { periode, detailContent } = module;
   const dispatch = useDispatch();

   // bool
   const [isLoading, setIsLoading] = useState(true);

   const getData = (nim, periode) => {
      const formData = { nim, periode };

      setIsLoading(true);
      const fetch = h.post(`/akademik/magang/getdata`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         dispatch(setModule({ ...module, detailContent: data.data }));
      });
      fetch.finally(() => {
         setIsLoading(false);
      });
   };

   useLayoutEffect(() => {
      getData(h.parse("preferred_username", init), h.parse("nama_singkat", periode));
      return () => {};
   }, [init, periode]);

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

   return isLoading ? (
      loader
   ) : (
      <React.Suspense fallback={loader}>
         <Header />
         {h.objLength(detailContent) ? (
            <Switch condition={detailContent.status}>
               <Case value="1">
                  <Review />
               </Case>
               <Case value="2">
                  <Diterima />
               </Case>
            </Switch>
         ) : (
            <Pengajuan />
         )}
      </React.Suspense>
   );
};
export default Context;
