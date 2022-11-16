import Box from '@mui/material/Box';
import { DataGrid, GridColDef, GridToolbarContainer, GridToolbarDensitySelector, GridToolbarExport } from '@mui/x-data-grid';
import { Incidentsobj } from '../../../types';

import styles from './datagrid.module.css';
import MainTable from './mainTable';



const CustomToolbar = () => {
  return (
    <GridToolbarContainer>
      <GridToolbarDensitySelector></GridToolbarDensitySelector>
      
      <GridToolbarExport printOptions={{ disableToolbarButton: true }} />


    </GridToolbarContainer>
  );
}
const Table = () =>
{
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
    let Incidentrows:Incidentsobj[] = [
        {id:0,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:1,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:2,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:3,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:4,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:5,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:6,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:7,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:8,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:9,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:10,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
        {id:11,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},

        
    ]
    return (
      <div className={styles.tabel}>
        <MainTable data={Incidentrows} columns={columns}/>
    </div>
    )
}
export default Table;