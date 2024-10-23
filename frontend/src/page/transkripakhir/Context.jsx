import React from "react";
import { Bars } from "react-loader-spinner";

const Header = React.lazy(() => import("./Header"));
const Lists = React.lazy(() => import("./Lists"));

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
         wrapperClass="page-loader flex-column justify-content-center"
      />
   );

   return (
      <React.Suspense fallback={loader}>
         <Header />
         <Lists />
      </React.Suspense>
   );
};
export default Context;
