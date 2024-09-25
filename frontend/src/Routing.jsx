import React from "react";
import { Bars } from "react-loader-spinner";
import { Route, Routes } from "react-router-dom";

const Home = React.lazy(() => import("./page/Home"));
const Aktif = React.lazy(() => import("./page/aktif/Context"));

const Routing = () => {
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
      <Routes>
         <Route path="/" loader={loader} element={<Home />} />
         <Route path="/aktif" loader={loader} element={<Aktif />} />
      </Routes>
   );
};
export default Routing;
