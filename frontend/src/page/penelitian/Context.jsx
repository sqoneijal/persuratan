import React, { useLayoutEffect, useState } from "react";
import { Bars } from "react-loader-spinner";
import { useDispatch, useSelector } from "react-redux";
import * as h from "~/src/Helpers";
import { setModule } from "~/src/redux";

const FormsPengajuan = React.lazy(() => import("./FormsPengajuan"));
const Header = React.lazy(() => import("./Header"));
const SudahMengajukan = React.lazy(() => import("./SudahMengajukan"));

const Context = () => {
   const { module, init } = useSelector((e) => e.redux);
   const { periode } = module;
   const dispatch = useDispatch();

   // bool
   const [isLoading, setIsLoading] = useState(true);
   const [apakahSudahMengajukan, setApakahSudahMengajukan] = useState(false);

   const getStatus = (nim, periode) => {
      const formData = { nim, periode };

      setIsLoading(true);
      const fetch = h.post(`/akademik/penelitian/status`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         if (data.status && h.objLength(data.data)) {
            setApakahSudahMengajukan(true);
            dispatch(setModule({ ...module, detailContent: data.data }));
         }
      });
      fetch.finally(() => {
         setIsLoading(false);
      });
   };

   useLayoutEffect(() => {
      getStatus(h.parse("preferred_username", init), h.parse("nama_singkat", periode));
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

   const props = { setApakahSudahMengajukan };

   return isLoading ? (
      loader
   ) : (
      <React.Suspense fallback={loader}>
         <Header />
         {apakahSudahMengajukan ? <SudahMengajukan /> : <FormsPengajuan {...props} />}
      </React.Suspense>
   );
};
export default Context;
