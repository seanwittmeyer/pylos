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
using Autodesk.Revit.DB.Architecture;

namespace HarvestProjectData
{
    class ElementPropertiesWrapper
    {
        public List<ElementProps> m_model_elements = new List<ElementProps>();
        public List<RevitRoom> m_model_rooms = new List<RevitRoom>();
        public List<RevitView> m_model_views = new List<RevitView>();

        public static string NullDescrip = string.Empty;

        // Common Revit project properties:
        public DateTime RevitFileTimeStampUtc { get; private set; }
        // UnixTimeStamp Prop
        public long FileTimeStampCounter { get; private set; }
        public long ElementCount { get; private set; }
        public Nullable<long> FileSize { get; private set; }
        public long RoomCount { get; private set; }

        public string RevitFilePath { get; private set; }
        public bool IsBim360Model { get; private set; }
        public string ProjectNumber { get; private set; }
        public string ProjectName { get; private set; }
        public string FileVersion { get; private set; }

        public Guid ModelGuid { get; private set; }
        public Guid ProjectGuid { get; private set; }
        public string CentralServerPath { get; private set; }
        public bool CloudPath { get; private set; }
        public bool ServerPath { get; private set; }

        public long WarningsCount { get; private set; }

        //public long CadImportCount { get; private  set; }
        //public long CadLinkCount { get; private set; }

        //Constructor
        public ElementPropertiesWrapper(Document revitDoc)
        {
            // Get single timestamp to apply to all elements this query
            RevitFileTimeStampUtc = DateTime.UtcNow;
            FileTimeStampCounter = UtcDateTimeToUnix(RevitFileTimeStampUtc);

            RevitFilePath = revitDoc.PathName;

            IsBim360Model = revitDoc.IsModelInCloud;
            ProjectNumber = revitDoc.ProjectInformation.Number;
            ProjectName = revitDoc.ProjectInformation.Name;
            FileVersion = revitDoc.Application.VersionNumber;

            if (!IsBim360Model)
            {
                FileInfo fi = new FileInfo(revitDoc.PathName);
                FileSize = fi.Length / 1024;
            }
            else
                FileSize = null;

            try
            {
                if (IsBim360Model)
                {
                    ModelPath mp = revitDoc.GetCloudModelPath();
                    ModelGuid = mp.GetModelGUID();
                    ProjectGuid = mp.GetProjectGUID();
                    CentralServerPath = mp.CentralServerPath;
                    CloudPath = mp.CloudPath;
                    ServerPath = mp.ServerPath;
                }
                
            }
            catch (Exception ex)
            {               
                CentralServerPath = null;
                CloudPath = false;
                ServerPath = false;
            }

            WarningsCount = revitDoc.GetWarnings().Count;
            
            // Get ALL elements:
            IEnumerable<Element> allElements = new FilteredElementCollector(revitDoc)
                .WhereElementIsNotElementType()
                .ToElements()
                .Where(e => e.Name != string.Empty)
                .Where(c => null != c.Category)
                .Where(c => !c.Category.Name.Contains("Sketch"));


            this.ElementCount = allElements.Count();
  

            foreach (Element e in allElements)
            {
                ElementProps props = new ElementProps(e);
                
                // Collect the element:
                m_model_elements.Add(props);
            }

            // ROOMS
            List<SpatialElement> rms = new FilteredElementCollector(revitDoc)
                .OfClass(typeof(SpatialElement))
                .Cast<SpatialElement>()
                .ToList<SpatialElement>();
                
            if (rms.Count > 0)
            {
                foreach (SpatialElement se in rms)
                {
                    Room r = se as Room;
                    if (null != r) m_model_rooms.Add(new RevitRoom(r));
                }
            }
            this.RoomCount = m_model_rooms.Count;

            // VIEWS 
            List<View> vws = new FilteredElementCollector(revitDoc)
                .OfClass(typeof(View))
                .Cast<View>()
                .ToList<View>();

            foreach (View v in vws)
            {
                m_model_views.Add(new RevitView(v));
            }


        }



        private long UtcDateTimeToUnix(DateTime TimeStampUtc)
        {
            // Unix timestamp is seconds past epoch
            System.DateTime dtDateTime = new DateTime(1970, 1, 1, 0, 0, 0, 0, System.DateTimeKind.Utc);

            return (long)(TimeStampUtc - dtDateTime).TotalSeconds;
        }



        public void WriteToTabDelimitedText(string outputPath)
        {
            FileInfo outputFile = new FileInfo(outputPath);
            if (!Directory.Exists(outputFile.DirectoryName)) throw new DirectoryNotFoundException();

            string baseFileName = Path.Combine(outputFile.DirectoryName, Path.GetFileNameWithoutExtension( outputFile.FullName));


            using (TextWriter writer = new StreamWriter(baseFileName + "_PROJECT.tab"))
            {
                try
                {
                    // PROJECT INFO:
                    writer.WriteLine("CentralServerPath\t" + this.CentralServerPath);
                    writer.WriteLine("CloudPath\t" + this.CloudPath.ToString());
                    writer.WriteLine("ElementCount\t" + this.ElementCount.ToString());
                    writer.WriteLine("FileSizeInBytes\t" + this.FileSize.ToString());
                    writer.WriteLine("RoomCount\t" + this.RoomCount.ToString());
                    writer.WriteLine("FileTimeStampCounter\t" + this.FileTimeStampCounter.ToString());
                    writer.WriteLine("FileVersion\t" + this.FileVersion);
                    writer.WriteLine("IsBim360Model\t" + this.IsBim360Model.ToString());
                    writer.WriteLine("ModelGuid\t" + this.ModelGuid.ToString());
                    writer.WriteLine("ProjectGuid\t" + this.ProjectGuid.ToString());
                    writer.WriteLine("ProjectName\t" + this.ProjectName);
                    writer.WriteLine("ProjectNumber\t" + this.ProjectNumber);
                    writer.WriteLine("RevitFilePath\t" + this.RevitFilePath);
                    writer.WriteLine("RevitFileTimeStampUtc\t" + this.RevitFileTimeStampUtc.ToLongDateString() + " @ " + this.RevitFileTimeStampUtc.ToLongTimeString());
                    writer.WriteLine("ServerPath\t" + this.ServerPath.ToString());
                    writer.WriteLine("WarningsCount\t" + this.WarningsCount.ToString());

                }
                catch (Exception ex)
                {
                    writer.WriteLine("FAILED: " + ex.Message);
                    writer.WriteLine("\nStack dump:\n\n" + ex.StackTrace);
                }
            }


            using (TextWriter writer = new StreamWriter(baseFileName + "_ELEMENTS.tab"))
            {
                try
                {
                    // ELEMENT INFO:
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
                    toJson.Serialize(jsonFile, this); // m_model_elements);
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
            if (IsViewSpecific)
            {
                OwnerViewId = el.OwnerViewId.IntegerValue;
            }
            else
            {
                OwnerViewId = null;
            }
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

        // Read-only field for linking to ProjectInfo
        public string RevitProjectPath { get; }


        // Specific stuff:
        public long ID { get; private set; }
        public string Name { get; private set; }
        public string ClassName { get; private set; }
        public string CategoryName { get; private set; }
        public string LevelName { get; private set; }
        public bool IsViewSpecific { get; private set; }
        public Nullable<long> OwnerViewId { get; private set; }

        public string OwnerViewName { get; private set; }

        public bool IsWorkshared { get; private set; }
        public string WorksetName { get; private set; }

        public string DesignOptionSet { get; private set; }
        public string DesignOptionName { get; private set; }

        public string PhaseName { get; private set; }
    }

    class RevitRoom
    {
        // Read-only field for linking to ProjectInfo
        public string RevitProjectPath { get;  }

        private Autodesk.Revit.DB.Architecture.Room _room;

        public long ElementId { get; private set; }
        public double Area { get; private set; }
        public string RoomName { get; private set; }
        public string RoomNumber { get; private set; }

        public bool IsPlaced { get
            { 
                return (null != _room.Location); }     
            }
        public bool IsBounded
        {
            get
            {
                return (_room.Area > 0);
            }
        }

        public string OptionName { get; private set; }
        public string EditedBy { get; private set; }
        public string PhaseName { get; private set; }
        public Nullable<double> Location_X { get; private set; }
        public Nullable<double> Location_Y { get; private set; }
        public Nullable<double> Location_Z { get; private set; }
        public Nullable<double> BaseOffset { get; private set; }
        public Nullable<double> LimitOffset { get; private set; }
        public Nullable<double> UnboundedHeight { get; private set; }
        public string LevelName { get; private set; }
        public string UpperLimitLevelName { get; private set; }
        public Nullable<double> Volume { get; private set; }
        

        public RevitRoom(Autodesk.Revit.DB.Architecture.Room room)
        {
            _room = room;

            this.ElementId = room.Id.IntegerValue;
            this.Area = room.Area;
            RoomName = room.get_Parameter(BuiltInParameter.ROOM_NAME).AsString();
            RoomNumber = room.get_Parameter(BuiltInParameter.ROOM_NUMBER).AsString();
            OptionName = (null == _room.DesignOption) ? string.Empty : _room.DesignOption.Name as string;
            EditedBy = (_room.Document.IsWorkshared) ? _room.get_Parameter(BuiltInParameter.EDITED_BY).AsString() : string.Empty;
            PhaseName = room.Document.GetElement(_room.get_Parameter(BuiltInParameter.ROOM_PHASE_ID).AsElementId()).Name;

            if (IsPlaced)
            { 
                LocationPoint pt = _room.Location as LocationPoint;
                if (null != pt)
                {
                    XYZ xyzPt = pt.Point;
                    Location_X = xyzPt.X;
                    Location_Y = xyzPt.Y;
                    Location_Z = xyzPt.Z;
                }
            }
            else
            {
                Location_X = Location_Y = Location_Z = null;
            }

            BaseOffset = _room.BaseOffset;
            LimitOffset = _room.LimitOffset;
            UnboundedHeight = _room.UnboundedHeight;
            LevelName = (null == _room.LevelId) ? null : _room.Level.Name;
            UpperLimitLevelName = (null == _room.UpperLimit) ? null : _room.UpperLimit.Name;
            Volume = (IsPlaced) ? _room.Volume : 0;
        }
    }

    class RevitView
    {
        private View _view;

        public long ElementId { get; private set; }
        public string ViewTypeName { get; private set; }
        public string Name { get; private set; }
        public string ViewTitle { get; private set; }
        public bool IsViewTemplate { get; private set; }
        public bool CanBePrinted { get; private set; }

        public RevitView(View theView)
        {
            ElementId = theView.Id.IntegerValue;
            _view = theView;
            ViewTypeName = theView.ViewType.ToString();
            Name = _view.Name;
            ViewTitle = _view.Title;
            IsViewTemplate = _view.IsTemplate;
            CanBePrinted = _view.CanBePrinted;


        }

        
    }

    class RevitFamily
    {

    }

    class RevitWarnings
    {
        
    }

    class RevitGroup
    {

    }
}
