import jsPDF from "jspdf";
import React, { useRef } from "react";
import { useSelector } from "react-redux";
import Switch, { Case } from "react-switch-case";
import * as h from "~/src/Helpers";
import ReportTemplate from "./ReportTemplate";

const FormsDiterima = React.lazy(() => import("./FormsDiterima"));

const StatusPengajuan = () => {
   const { module } = useSelector((e) => e.redux);
   const { detailContent } = module;
   const reportTemplateRef = useRef(null);

   const downloadPDF = () => {
      const doc = new jsPDF({
         format: "a4",
         unit: "px",
      });

      // Adding the fonts.
      doc.setFont("Inter-Regular", "normal");

      doc.html(reportTemplateRef.current, {
         async callback(doc) {
            doc.save("document");
         },
      });
      /* const options = {
         method: "POST",
         hostname: "us1.pdfgeneratorapi.com",
         port: null,
         path: "/api/v4/documents/generate",
         headers: {
            Authorization:
               "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiIxOTZlODcxZjBkZTRkOTFlMzYzMTM4NjIzNjY1OGE0NjQyMTFhODZjM2ExNDY0YmZiMzQxZWVhYTE0NGNmMTQzIiwic3ViIjoic3FvbmUuZGV2ZWxvcGVyQGdtYWlsLmNvbSIsImV4cCI6MTcyNjgxMDExOH0.R1lfG-S55yVE-IDsn0m150Fi5t38ce-F17FjeocFJF0",
            "Content-Type": "application/json",
         },
      };

      const req = http.request(options, function (res) {
         const chunks = [];

         res.on("data", function (chunk) {
            chunks.push(chunk);
         });

         res.on("end", function () {
            const body = Buffer.concat(chunks);
            console.log(body.toString());
         });
      });

      console.log(req); */
   };

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
            <div ref={reportTemplateRef} style={{ display: "none" }}>
               <ReportTemplate />
            </div>
         </Case>
      </Switch>
   );
};
export default StatusPengajuan;
