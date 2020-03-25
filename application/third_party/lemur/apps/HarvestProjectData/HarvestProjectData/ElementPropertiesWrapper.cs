using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.ComponentModel;
using System.Reflection;
using Newtonsoft.Json;
using Autodesk.Revit.DB;

namespace HarvestProjectData
{
    class ElementPropertiesWrapper
    {
        public List<ElementProps> m_model_elements = new List<ElementProps>();

        public static string NullDescrip = string.Empty;
  

        public ElementPropertiesWrapper(Document revitDoc)
        {
            // Get single timestamp to apply to all elements this query
            DateTime datestampUtc = DateTime.UtcNow;

            // Get all elements:
            IEnumerable<Element> allElements = new FilteredElementCollector(revitDoc)
                .WhereElementIsNotElementType()
                .ToElements()
                .Where(e => e.Name != string.Empty);

            // Project Info

            foreach (Element e in allElements)
            {
                ElementProps props = new ElementProps(e);
                props.RevitFileTimeStampUtc = datestampUtc;                
                
                // Collect the element:
                m_model_elements.Add(props);
            }



        }



        public void WriteToTabDelimitedText(string outputPath)
        {
            FileInfo outputFile = new FileInfo(outputPath);
            if (!Directory.Exists(outputFile.DirectoryName)) throw new DirectoryNotFoundException();
            
            using (TextWriter writer = new StreamWriter(outputFile.FullName))
            {
                try
                {
                    // Get Header from first element:
                    
                    StringBuilder header = new StringBuilder();
                    Type t = m_model_elements[0].GetType();
                    PropertyInfo[] props = t.GetProperties();

                    foreach (var p in props)
                    {                        
                        header.Append(p.Name + "\t");
                    }
                    writer.WriteLine(header.ToString());

                    // Get all of the values:
                    foreach (ElementProps elemProps in m_model_elements)
                    {
                        StringBuilder propListLine = new StringBuilder();
                        t = elemProps.GetType();
                        props = t.GetProperties();
                        foreach (var pVal in props)
                        {
                            // Parse the val:
                            string theVal = string.Empty;
                            string typeName = pVal.PropertyType.Name;
                            switch (typeName)
                            {
                                case "String":
                                    theVal = pVal.GetValue(elemProps).ToString();
                                    break;
                                case "Boolean":
                                    bool b = (bool)pVal.GetValue(elemProps);
                                    theVal = b.ToString();
                                    break;
                                case "Int32":
                                    int i = (int)pVal.GetValue(elemProps);
                                    theVal = i.ToString();
                                    break;
                                case "Double":
                                    double d = (double)pVal.GetValue(elemProps);
                                    theVal = d.ToString();
                                    break;
                                case "DateTime":
                                    DateTime date = (DateTime)pVal.GetValue(elemProps);
                                    theVal = date.ToShortDateString() + " " + date.ToShortTimeString();
                                    break;
                                default:
                                    break;


                            }
                            propListLine.Append(theVal + "\t");

                        }
                        writer.WriteLine(propListLine.ToString());
                    }

                }
                catch (Exception ex)
                {
                    writer.WriteLine("FAILED: " + ex.Message);
                    writer.WriteLine("\nStack dump:\n\n" + ex.StackTrace);
                }
                finally
                {
                    writer.Close();                    
                }
            }

        }

        public void WriteToExcel(string outputPath)
        {
            
           
            
        }

        public void WriteToSql() { }

        public void WriteToJson(string outputPath)
        {
            FileInfo outputFile = new FileInfo(outputPath);
            if (!Directory.Exists(outputFile.DirectoryName)) throw new DirectoryNotFoundException();


            using (StreamWriter jsonFile = File.CreateText(outputFile.FullName))
            {
                try
                {
                    JsonSerializer toJson = new JsonSerializer();
                    toJson.Serialize(jsonFile, m_model_elements);
                }
                catch (Exception ex)
                {
                    jsonFile.WriteLine("FAILED: " + ex.Message);
                    jsonFile.WriteLine("\nStack dump:\n\n" + ex.StackTrace);
                }
                finally
                {
                    jsonFile.Close();
                }
            }
        }

    }



    /// <summary>
    /// Class Wrapper for general elements
    /// </summary>
    class ElementProps
    {
        public ElementProps(Element el)
        {
            Document doc = el.Document;

            RevitFilePath = doc.PathName;
            
            IsBim360Model = doc.IsModelInCloud;
            ProjectNumber = doc.ProjectInformation.Number;
            ProjectName = doc.ProjectInformation.Name;
            FileVersion = doc.Application.VersionNumber;

            ID = el.Id.IntegerValue;
            Name = el.Name;
            ClassName = el.GetType().ToString().Replace("Autodesk.Revit.DB.", string.Empty);
            CategoryName = (null != el.Category)
                    ? el.Category.Name
                    : ElementPropertiesWrapper.NullDescrip;
            LevelName = (ElementId.InvalidElementId == el.LevelId)
                ? ElementPropertiesWrapper.NullDescrip
                : doc.GetElement(el.LevelId).Name;
            IsWorkshared = doc.IsWorkshared;

            if (IsWorkshared)
            {
                WorksetTable wsTbl = doc.GetWorksetTable();
                WorksetName = (WorksetId.InvalidWorksetId != el.WorksetId)
                ? wsTbl.GetWorkset(el.WorksetId).Name
                : ElementPropertiesWrapper.NullDescrip;
            }
            else { WorksetName = ElementPropertiesWrapper.NullDescrip; }

            IsViewSpecific = el.ViewSpecific;
            OwnerViewName = IsViewSpecific
                ? doc.GetElement(el.OwnerViewId).Name
                : string.Empty;

            if (null == el.DesignOption)
                DesignOptionSet = DesignOptionName = string.Empty;
            else
            {
                // Might need to parse this?..
                DesignOptionSet = el.DesignOption.Name;
                DesignOptionName = el.DesignOption.Name;
            }

            PhaseName = (el.CreatedPhaseId == ElementId.InvalidElementId)
                ? ElementPropertiesWrapper.NullDescrip
                : doc.GetElement(el.CreatedPhaseId).Name;

        }

        // Common stuff:
        public DateTime RevitFileTimeStampUtc { get; set; }
        
        public string RevitFilePath { get; private set; }       
        public bool IsBim360Model { get; private set; }
        public string ProjectNumber { get; private set; }
        public string ProjectName { get; private set; }
        public string FileVersion { get; private set; }

        // Specific stuff:
        public int ID { get; private set; }
        public string Name { get; private set; }
        public string ClassName { get; private set; }
        public string CategoryName { get; private set; }
        public string LevelName { get; private set; }
        public bool IsViewSpecific { get; private set; }        
        public string OwnerViewName { get; private set; }

        public bool IsWorkshared { get; private set; }
        public string WorksetName { get; private set; }

        public string DesignOptionSet { get; private set; }
        public string DesignOptionName { get; private set; }

        public string PhaseName { get; private set; }
    }

    class RevitRooms
    {

    }

    class RevitFamily
    {

    }

}
