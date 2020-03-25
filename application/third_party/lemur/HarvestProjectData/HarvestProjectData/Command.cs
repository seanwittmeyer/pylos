using System;
using System.Collections.Generic;
using System.Linq;
using System.IO;
using System.Text;
using System.Threading.Tasks;
using Autodesk.Revit.ApplicationServices;
using Autodesk.Revit.Attributes;
using Autodesk.Revit.Exceptions;
using Autodesk.Revit.DB;
using System.Diagnostics;
using Autodesk.Revit.UI;
using DesignAutomationFramework;


namespace HarvestProjectData
{
#if RUNLOCAL

    [Transaction(TransactionMode.Manual)]
    public class ExportProjectHealthData : IExternalCommand
    {

        // Z:\SEA\Projects\00018.zlab\Design Computation\Development\2019\Project Health Dashboard
        // https://forge.autodesk.com/en/docs/design-automation/v3/tutorials/revit/

        public Autodesk.Revit.UI.Result Execute(ExternalCommandData commandData, ref string message, ElementSet elements)
        {
            Result returnResult = Result.Succeeded;

            try
            {
                Document doc = commandData.Application.ActiveUIDocument.Document;

                ElementPropertiesWrapper propsWrapper = new ElementPropertiesWrapper(doc);

                FileInfo rvtPath;
                // What if it's a Cloud model?

                string FilePath;

                if (doc.IsModelInCloud)
                {
                    FilePath = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments), doc.Title);
                }
                else
                {
                    rvtPath = new FileInfo(doc.PathName);
                    FilePath = Path.Combine(
                        rvtPath.DirectoryName,
                        Path.GetFileNameWithoutExtension(rvtPath.FullName));
                }

                // Write to TEXT:
                //propsWrapper.WriteToTabDelimitedText(FilePath + ".tab");

                // Write to JSON:
                propsWrapper.WriteToJson(FilePath + ".json");
            }
            catch (Exception ex)
            {
                message = "ZGF Add-in HarvestProjectData encountered an exception.\nException details:\n\n" + ex.Message;
                returnResult = Result.Failed;
            }
            
            return returnResult;

        }
    }
#else
    [Transaction(TransactionMode.Manual)]
    [Regeneration(RegenerationOption.Manual)]
    public class ExportProjectHealthData : IExternalDBApplication
    {

        public ExternalDBApplicationResult OnStartup(ControlledApplication application)
        {

            DesignAutomationBridge.DesignAutomationReadyEvent += HandleDesignAutomationReadyEvent;
            return ExternalDBApplicationResult.Succeeded;
        }

        private void HandleDesignAutomationReadyEvent(object sender, DesignAutomationReadyEventArgs e)
        {
            e.Succeeded = true;
            Execute(e.DesignAutomationData.RevitDoc);
        }

        public ExternalDBApplicationResult OnShutdown(ControlledApplication application)
        {
            return ExternalDBApplicationResult.Succeeded;
        }

        // Z:\SEA\Projects\00018.zlab\Design Computation\Development\2019\Project Health Dashboard
        // https://forge.autodesk.com/en/docs/design-automation/v3/tutorials/revit/

        public void Execute(Document document)
        {

            ElementPropertiesWrapper propsWrapper = new ElementPropertiesWrapper(document);

            FileInfo rvtPath;
            // What if it's a Cloud model?

            string FilePath;
                    
            if (document.IsModelInCloud)
            {
                FilePath = Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.MyDocuments), document.Title);
            }
            else
            {
                rvtPath = new FileInfo(document.PathName);
                FilePath = Path.Combine(
                                rvtPath.DirectoryName,
                                Path.GetFileNameWithoutExtension(rvtPath.FullName));
            }

            // Write to TEXT:
            //propsWrapper.WriteToTabDelimitedText(FilePath + ".tab");

            // Write to JSON:
            propsWrapper.WriteToJson(FilePath + ".json");
        }

        
        // TODO: Make sure this works with Pylos
        private String[] ErrorLog(string code, string message1, string message2 = null)
        {
            return Array.Empty<string>();
        }
    }
#endif
}

