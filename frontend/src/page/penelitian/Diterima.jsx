import React from "react";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const Diterima = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   return (
      <div className="about-content">
         <div className="section-header left-style">
            <h5 className="cate">Keterangan Surat Penelitian</h5>
            {h.detail_label("Nomor Surat", h.parse("no_surat", detailContent))}
            {h.detail_label("Tujuan Surat", h.parse("surat_kepada", detailContent))}
            {h.detail_label("Judul Penelitian", h.parse("judul_penelitian", detailContent))}
            {h.buttons(`Download PDF`, false, {
               onClick: () => window.open(`${window.apiPath}/akademik/penelitian/cetak/${h.parse("id", detailContent)}`, "_blank"),
            })}
         </div>
      </div>
   );
};
export default Diterima;
