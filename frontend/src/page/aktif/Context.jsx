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
      const fetch = h.post(`/akademik/surataktifkuliah/status`, formData);
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

   const getStatusPembayaranSPP = (nim, periode) => {
      const formData = { nim, periode };

      setIsLoading(true);
      const fetch = h.post(`/sevima/statuspembayaranspp`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         if (data.status) {
            if (data.data) {
               initPage(nim, periode);
            } else {
               h.notification(false, "Anda belum melakukan pembayaran SPP semester aktif sekarang!");
               return;
            }
         }
      });
   };

   useLayoutEffect(() => {
      if (h.objLength(periode) && h.objLength(biodata)) getStatusPembayaranSPP(h.parse("nim", biodata), periode.nama_singkat);
      return () => {};
   }, [periode, biodata]);

   const props = { initPage };

   return isLoading ? (
      loader
   ) : (
      <React.Suspense fallback={loader}>
         <Header />
         {h.objLength(detailContent) ? <StatusPengajuan /> : <FormsPengajuan {...props} />}
      </React.Suspense>
   );
};
export default Context;
