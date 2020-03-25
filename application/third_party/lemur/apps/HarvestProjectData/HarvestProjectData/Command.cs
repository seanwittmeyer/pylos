using System;
using System.Collections.Generic;
using System.Linq;
using System.IO;
using System.Text;
using System.Threading.Tasks;
using Autodesk.Revit.Attributes;
using Autodesk.Revit.Exceptions;
using Autodesk.Revit.UI;
using Autodesk.Revit.DB;
using System.Diagnostics;


namespace HarvestProjectData
{
    [Transaction(TransactionMode.Manual)]
    public class ExportProjectHealthData : IExternalCommand
    {

        // Z:\SEA\Projects\00018.zlab\Design Computation\Development\2019\Project Health Dashboard
        // https://forge.autodesk.com/en/docs/design-automation/v3/tutorials/revit/

        public Autodesk.Revit.UI.Result Execute(ExternalCommandData commandData, ref string message, ElementSet elements)
        {
            Document doc = commandData.Application.ActiveUIDocument.Document;

            ElementPropertiesWrapper propsWrapper = new ElementPropertiesWrapper(doc);

            FileInfo rvtPath = new FileInfo(doc.PathName);
            // What if it's a Cloud model?
            string FilePath = Path.Combine(
                rvtPath.DirectoryName, 
                Path.GetFileNameWithoutExtension(rvtPath.FullName));

            // Write to TEXT:

            //propsWrapper.WriteToTabDelimitedText(FilePath + ".tab");

            // Write to JSON:
            propsWrapper.WriteToJson(FilePath + ".json");
            


            return Result.Succeeded;

        }
    }
}
