import { GridColDef} from '@mui/x-data-grid';
import styles from './datagrid.module.css';
import MainTable from './mainTable';
import { Incidents,ITable } from '../../../types';



const Table = ({incidents,info}:ITable) =>
{
  let showedIncidents:Incidents[] = [];
  if(info == "open" && incidents !== undefined)
  {
    incidents.map((incident:Incidents) => {if((incident.Status != "" && incident.Status != null && incident.Status != "Closed")){showedIncidents.push(incident)}})
  }
  else if(info == "closed" && incidents !== undefined)
  {
    incidents.map((incident:Incidents) => {if(incident.Status == "Closed"){showedIncidents.push(incident)}})
  }
  else if(info == "waiting" && incidents !== undefined)
  {
    incidents.map((incident:Incidents) => {if(incident.Status == "" || incident.Status == null){showedIncidents.push(incident)}})
  }
    const columns: GridColDef[] = [
        { field: 'info', headerName: '', width: 75 },
        {
          field: 'id',
          headerName: 'ID',
          width: 100,
        },
        {
          field: 'Student',
          headerName: 'Student',
          width: 175,
        },
        {
          field: 'SerialNumber',
          headerName: 'Serial number',
          width: 225,
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
         width:150
        },
        {
          field:"Created",
          editable:true,
          type:"singleSelect",
          valueOptions:["V1","V2"],
          width:175
         },
        
      ];
    return (
      <div className={styles.tabel}>
        <MainTable data={showedIncidents} columns={columns}/>
    </div>
    )
}
export default Table;