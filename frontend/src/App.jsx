import { configureStore } from "@reduxjs/toolkit";
import axios from "axios";
import Keycloak from "keycloak-js";
import React, { useLayoutEffect, useState } from "react";
import { createRoot } from "react-dom/client";
import { Bars } from "react-loader-spinner";
import { Provider, useDispatch, useSelector } from "react-redux";
import { BrowserRouter as Router } from "react-router-dom";
import * as h from "~/src/Helpers";
import { setInit, setModule } from "~/src/redux";
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
import "~/node_modules/toastr/build/toastr.css";

const Header = React.lazy(() => import("./Header"));
const Routing = React.lazy(() => import("./Routing"));

const App = () => {
   const { module } = useSelector((e) => e.redux);
   const dispatch = useDispatch();

   // bool
   const [isLoading, setIsLoading] = useState(true);

   const initPage = (nim) => {
      axios
         .all([h.get(`/sevima/periodeaktif`), h.get(`/sevima/biodata/${nim}`)])
         .then(
            axios.spread((...res) => {
               const [periode, biodata] = res;
               if (h.objLength(periode.data) && h.objLength(biodata.data)) {
                  if (periode.data.status && biodata.data.status) {
                     dispatch(setModule({ ...module, periode: periode.data.data, biodata: biodata.data.data }));
                  } else {
                     h.notification(false, "Gagal mengambil data dari sevima!");
                     return;
                  }
               }
            })
         )
         .finally(() => {
            setIsLoading(false);
         });
   };

   useLayoutEffect(() => {
      const keycloak = new Keycloak({
         url: "https://keycloak.ar-raniry.ac.id/auth/",
         realm: "uinar",
         clientId: "mael",
      });

      keycloak
         .init({
            onLoad: "check-sso",
            // silentCheckSsoRedirectUri: `${location.origin}/silent-check-sso.html`,
            checkLoginIframe: false,
         })
         .then((res) => {
            if (!res) {
               keycloak.login();
            }
         });

      keycloak.onAuthSuccess = () => {
         dispatch(setInit(keycloak.idTokenParsed));
         initPage(keycloak.idTokenParsed.preferred_username);
      };
      return () => {};
   }, []);

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
