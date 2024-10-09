import React from "react";
import { Container } from "react-bootstrap";
import { useSelector } from "react-redux";
import { Link, useLocation } from "react-router-dom";
import logo from "~/assets/logo_uin.svg";
import * as h from "~/src/Helpers";
import { Each } from "./Each";

const Header = () => {
   const { init } = useSelector((e) => e.redux);
   const location = useLocation();

   const navigation = [
      { label: "Aktif", pathname: "/aktif" },
      // { label: "Cuti", pathname: "/cuti" },
      // { label: "Masih Kuliah", pathname: "/masihkuliah" },
      { label: "Tidak Menerima Beasiswa", pathname: "/tidakmenerimabeasiswa" },
      { label: "Penelitian", pathname: "/penelitian" },
      { label: "Magang", pathname: "/magang" },
   ];

   return (
      <header className="header-section inner-header">
         <Container>
            <div className="header-wrapper">
               <div className="logo">
                  <Link to="/">
                     <img src={logo} alt="logo" />
                  </Link>
               </div>
               {h.objLength(init) && (
                  <ul className="menu">
                     <Each
                        of={navigation}
                        render={(row) => (
                           <li className="active-parent">
                              <Link to={row.pathname} className={h.parse("pathname", row) === h.parse("pathname", location) ? "active" : ""}>
                                 {h.parse("label", row)}
                              </Link>
                           </li>
                        )}
                     />
                  </ul>
               )}
               <div className="header-bar d-lg-none">
                  <span />
                  <span />
                  <span />
               </div>
            </div>
         </Container>
      </header>
   );
};
export default Header;
