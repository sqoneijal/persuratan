import lozad from "lozad";
import React, { useLayoutEffect } from "react";

const Header = () => {
   useLayoutEffect(() => {
      lozad().observe();
      return () => {};
   }, []);

   return (
      <section className="page-header bg_img oh lozad" data-background-image="/assets/banner/page-header.webp">
         <div className="bottom-shape d-none d-md-block">
            <img data-src="/assets/banner/page-header-2.webp" alt="css" className="lozad" />
         </div>
         <div className="page-left-thumb">
            <img data-src="assets/banner/privacy-header.webp" alt="bg" className="lozad" />
         </div>
         <div className="container">
            <div className="page-header-content cl-white">
               <h2 className="title">Surat Aktif Kuliah</h2>
            </div>
         </div>
      </section>
   );
};
export default Header;
