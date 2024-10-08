import React, { useLayoutEffect, useState } from "react";
import { Bars } from "react-loader-spinner";
import { useDispatch, useSelector } from "react-redux";
import Switch, { Case, Default } from "react-switch-case";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const Header = React.lazy(() => import("./Header"));
const FormsPengajuan = React.lazy(() => import("./FormsPengajuan"));
const Review = React.lazy(() => import("./Review"));
const Diterima = React.lazy(() => import("./Diterima"));

const Context = () => {
   const { module } = useSelector((e) => e.redux);
   const { biodata, periode, detailContent } = module;
   const dispatch = useDispatch();

   // bool
   const [isLoading, setIsLoading] = useState(true);

   const getData = (nim, periode) => {
      const formData = { nim, periode };

      setIsLoading(true);
      const fetch = h.post(`/akademik/tidakmenerimabeasiswa/getdata`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         dispatch(setModule({ ...module, detailContent: data }));
      });
      fetch.finally(() => {
         setIsLoading(false);
      });
   };

   useLayoutEffect(() => {
      getData(h.parse("nim", biodata), h.parse("nama_singkat", periode));
      return () => {};
   }, [biodata, periode]);

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
            <Switch condition={detailContent.is_approved}>
               <Case value="-">
                  <Review />
               </Case>
               <Case value="t">
                  <Diterima />
               </Case>
               <Default>
                  <FormsPengajuan />
               </Default>
            </Switch>
         ) : (
            <FormsPengajuan />
         )}
      </React.Suspense>
   );
};
export default Context;
