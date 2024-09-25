import React from "react";
import { useSelector } from "react-redux";
import Switch, { Case } from "react-switch-case";
import * as h from "~/src/Helpers";

const FormsDiterima = React.lazy(() => import("./FormsDiterima"));

const StatusPengajuan = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;

   const downloadPDF = () => {};

   return (
      <Switch condition={detailContent.status}>
         <Case value="0">
            <h3 className="title">Status Pengajuan</h3>
            <p>
               Status pengajuan surat aktif kuliah Anda sedang direview oleh akademik fakultas. Untuk informaasi lebih lanjut dapat menghubungi pihak
               akademik fakultas Anda masing - masing!
            </p>
         </Case>
         <Case value="1">
            <h3 className="title">Status Pengajuan</h3>
            <p>Status pengajuan surat aktif kuliah Anda disetujui!</p>
            <FormsDiterima />
            <div className="mt-5">
               {h.buttons(`Download PDF`, false, {
                  onClick: () => downloadPDF(),
               })}
            </div>
         </Case>
      </Switch>
   );
};
export default StatusPengajuan;
