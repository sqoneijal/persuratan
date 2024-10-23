import lozad from "lozad";
import React, { useLayoutEffect } from "react";

const Header = () => {
   useLayoutEffect(() => {
      lozad().observe();
      return () => {};
   }, []);

   return (
      <section className="page-header bg_img lozad" data-background-image="/assets/banner/page-header.webp">
         <div className="bottom-shape d-none d-md-block">
            <img data-src="/assets/banner/page-header-2.webp" alt="css" className="lozad" />
         </div>
         <div className="container">
            <div className="page-header-content cl-white">
               <h2 className="title">Kartu Hasil Studi</h2>
            </div>
         </div>
      </section>
   );
};
export default Header;
