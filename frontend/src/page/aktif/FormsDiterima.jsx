import React from "react";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const FormsDiterima = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   return (
      <React.Fragment>
         {h.detail_label("Nomor Surat", h.parse("no_surat", detailContent))}
         {h.detail_label("Tanggal Surat", h.parse("tgl_surat", detailContent, "date"))}
      </React.Fragment>
   );
};
export default FormsDiterima;
