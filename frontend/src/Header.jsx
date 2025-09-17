import { useRef } from "react";
import { Container } from "react-bootstrap";
import { useSelector } from "react-redux";
import { Link, useLocation } from "react-router-dom";
import logo from "~/assets/logo_uin.svg";
import * as h from "~/src/Helpers";
import { Each } from "./Each";

const Header = () => {
   const { init } = useSelector((e) => e.redux);
   const location = useLocation();
   const menu = useRef(null);

   const navigation = [
      { label: "KHS", pathname: "/khs" },
      { label: "Transkrip", pathname: "/transkripakhir" },
      { label: "Aktif", pathname: "/aktif" },
      // { label: "Cuti", pathname: "/cuti" },
      // { label: "Masih Kuliah", pathname: "/masihkuliah" },
      { label: "Tidak Menerima Beasiswa", pathname: "/tidakmenerimabeasiswa" },
      { label: "Penelitian", pathname: "/penelitian" },
      { label: "Magang", pathname: "/magang" },
      { label: "KPM", pathname: "/sertifikatkpm" },
      { label: "Refund", pathname: "/refund" },
   ];

   const handleClickMobileNav = (e) => {
      e.preventDefault();
      const overlay = document.getElementsByClassName("overlay");

      if (overlay.length > 0) {
         menu.current.classList.remove("active");
         document.body.removeChild(overlay[0]);
         return;
      }

      menu.current.classList.add("active");

      const overlayDiv = document.createElement("div");
      overlayDiv.className = "overlay active";
      document.body.insertBefore(overlayDiv, document.body.firstChild);
   };

   const handleMobileNavClick = () => {
      menu.current.classList.remove("active");
      const overlay = document.getElementsByClassName("overlay");
      overlay.length > 0 && document.body.removeChild(overlay[0]);
   };

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
                  <ul className="menu" ref={menu}>
                     <Each
                        of={navigation}
                        render={(row) => (
                           <li className="active-parent">
                              <Link
                                 to={row.pathname}
                                 className={h.parse("pathname", row) === h.parse("pathname", location) ? "active" : ""}
                                 onClick={handleMobileNavClick}>
                                 {h.parse("label", row)}
                              </Link>
                           </li>
                        )}
                     />
                  </ul>
               )}
               <div className="header-bar d-lg-none" onClick={handleClickMobileNav}>
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
