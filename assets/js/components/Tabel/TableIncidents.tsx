import {GridColDef} from '@mui/x-data-grid';
import MainTable from './mainTable';


interface Incident
{
    id:number
    Student:string,
    SerialNumber:string,
    Problem:string,
    Status:string,
    Created:string,
    
}
interface Table
{
  incidents:Incident[]
  info:string
}
const Table = ({incidents,info}:Table) =>
{
  let showedIncidents:Incident[] = [];
  if(info == "open")
  {
    incidents.map((incident:Incident) => {if((incident.Status != "" && incident.Status != null && incident.Status != "Closed")){showedIncidents.push(incident)}})
  }
  else if(info == "closed")
  {
    incidents.map((incident:Incident) => {if(incident.Status == "Closed"){showedIncidents.push(incident)}})
  }
  else if(info == "waiting")
  {
    incidents.map((incident:Incident) => {if(incident.Status == "" || incident.Status == null){showedIncidents.push(incident)}})
  }
    const columns: GridColDef[] = [
        { field: 'info', headerName: '', width: 75 },
        {
          field: 'id',
          headerName: 'ID',
          width: 150,
        },
        {
          field: 'Student',
          headerName: 'Student',
          width: 230,
        },
        {
          field: 'SerialNumber',
          headerName: 'Serial number',
          width: 260,
        },
        {
          field: 'Problem',
          headerName: 'Problem',
          width: 250,
        },
        {
         field:"Status",
         editable:true,
         type:"singleSelect",
         valueOptions:["V1","V2"],
         width:250
        },
        {
          field:"Created",
          editable:true,
          type:"singleSelect",
          valueOptions:["V1","V2"],
          width:250
         },
        
      ];
    
    return (
        <div>
          <MainTable data={showedIncidents} columns={columns}/>
        </div>
    )
}
export default Table;