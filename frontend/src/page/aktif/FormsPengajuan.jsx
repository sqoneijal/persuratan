import { useState } from "react";
import { useSelector } from "react-redux";
import * as h from "~/src/Helpers";

const FormsPengajuan = ({ initPage }) => {
   const { init, module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   // bool
   const [isSubmit, setIsSubmit] = useState(false);

   const submit = (e) => {
      e.preventDefault();
      const formData = { nim: h.parse("preferred_username", init), periode: h.parse("detailNavActive", detailContent) };

      setIsSubmit(true);
      const fetch = h.post(`/akademik/surataktifkuliah/pengajuan`, formData);
      fetch.then((res) => {
         if (typeof res === "undefined") return;

         const { data } = res;
         if (typeof data.code !== "undefined" && h.parse("code", data) !== 200) {
            h.notification(false, h.parse("message", data));
            return;
         }

         if (data.status) {
            window.location.reload();
         } else {
            h.notification(false, data.message);
         }
      });
      fetch.finally(() => {
         setIsSubmit(false);
      });
   };

   return (
      <div className="post-details">
         <div className="post-inner">
            <div className="post-header">
               <h3 className="title">Apakah anda ingin mengajukan surat keterangan aktif kuliah!</h3>
            </div>
            <div className="tags-area">
               {h.buttons(`Ajukan Surat Keterangan Aktif Kuliah`, isSubmit, {
                  onClick: isSubmit ? null : submit,
               })}
            </div>
         </div>
      </div>
   );
};
export default FormsPengajuan;
