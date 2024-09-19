import { configureStore } from "@reduxjs/toolkit";
import Keycloak from "keycloak-js";
import React, { useLayoutEffect } from "react";
import { createRoot } from "react-dom/client";
import { Bars } from "react-loader-spinner";
import { Provider } from "react-redux";
import { HashRouter as Router } from "react-router-dom";
import redux from "./redux";

import "~/assets/css/all.min.css";
import "~/assets/css/animate.css";
import "~/assets/css/bootstrap.min.css";
import "~/assets/css/flaticon.css";
import "~/assets/css/jquery-ui.min.css";
import "~/assets/css/magnific-popup.css";
import "~/assets/css/main.css";
import "~/assets/css/nice-select.css";
import "~/assets/css/owl.min.css";

const Header = React.lazy(() => import("./Header"));
const Routing = React.lazy(() => import("./Routing"));

const App = () => {
   useLayoutEffect(() => {
      const keycloak = new Keycloak({
         url: "https://keycloak.ar-raniry.ac.id/auth/",
         realm: "uinar",
         clientId: "siakad",
      });

      // keycloak.init({ onLoad: "login-required" }).then((res) => {
      //    console.log(res);
      // });
      return () => {};
   }, []);

   return (
      <React.Suspense
         fallback={
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
         }>
         <Header />
         <Routing />
      </React.Suspense>
   );
};

const store = configureStore({
   reducer: { redux },
});

const container = document.getElementById("root");
const root = createRoot(container);
root.render(
   <Provider store={store}>
      <Router>
         <App />
      </Router>
   </Provider>
);

if (process.env.NODE_ENV === "development") {
   new EventSource("http://localhost:8081/esbuild").addEventListener("change", () => location.reload());
}
