import lozad from "lozad";
import React, { useLayoutEffect } from "react";
import { Container } from "react-bootstrap";

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
         <Container>
            <div className="page-header-content cl-white">
               <h2 className="title">Pernyataan Tidak Menerima Beasiswa</h2>
            </div>
         </Container>
      </section>
   );
};
export default Header;
