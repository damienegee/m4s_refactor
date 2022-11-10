import Box from '@mui/material/Box';
import { DataGrid, GridColDef, GridToolbarContainer, GridToolbarDensitySelector, GridToolbarExport } from '@mui/x-data-grid';

import styles from './datagrid.module.css';

interface Incidentsobj
{
    id:number
    Student:string,
    SerialNumber:string,
    Problem:string,
    Status:string,
    Created:string,
    
}

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
        <div>
          <div>
        <Box sx={{ height:'700px', width: '100%' }} className={styles.box}>
            <DataGrid 
                components={{ Toolbar: CustomToolbar }}
                rows={Incidentrows}
                columns={columns}
                rowHeight={50} {...Incidentrows}
                checkboxSelection
                disableSelectionOnClick
                autoPageSize={true}

            
            />
        </Box>
        </div>
        </div>
    )
}
export default Table;