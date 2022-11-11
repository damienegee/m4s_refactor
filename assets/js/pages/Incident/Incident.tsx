import { useState } from 'react';
import styles from  '../page.module.css'
import { Box,Tab } from '@mui/material';
import {TabPanel,TabContext,TabList} from '@mui/lab';
import Header from "../../components/Header/Header";
import Sidebar from "../../components/Sidebar/Sidebar";
import Table from '../../components/Tabel/TableIncidents';
import Btn from '../../components/Button/Btn';
import { Link } from 'react-router-dom';
import AddRoundedIcon from '@mui/icons-material/AddRounded';

interface Incident
{
    id:number
    Student:string,
    SerialNumber:string,
    Problem:string,
    Status:string,
    Created:string,
    
}
let incidents:Incident[] = [
  {id:0,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
  {id:1,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Closed",Created:"Demo vestiging"},
  {id:2,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
  {id:3,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"",Created:"Demo vestiging"},
  {id:4,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"",Created:"Demo vestiging"},
  {id:5,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
  {id:6,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Restored",Created:"Demo vestiging"},
  {id:7,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Closed",Created:"Demo vestiging"},
  {id:8,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"",Created:"Demo vestiging"},
  {id:9,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Rejected",Created:"Demo vestiging"},
  {id:10,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Closed",Created:"Demo vestiging"},
  {id:11,Student:"DEMO00",SerialNumber:"DEMODEV00",Problem:"DEMO00",Status:"Restored",Created:"Demo vestiging"},
]

const Incident = () => {
  const [value, setValue] = useState('1');
  const handleChange = (event: React.SyntheticEvent, newValue: string) => {
    setValue(newValue);
  };
  return (
    <div className={styles.container}>
        <Sidebar/>
        <div className={styles.pageContainer}>
            <Header title="Indicents"/>
            <Box sx={{ width: '100%', typography: 'body1' }}>
              <TabContext value={value}>
                <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                  <TabList onChange={handleChange} aria-label="lab API tabs example">
                    <Tab label="Open" value="1" />
                    <Tab label="Awaiting approval" value="2" />
                    <Tab label="Closed" value="3" />
                    <Tab label="Graph" value="4" />
                  </TabList>
                </Box>
                
                <Btn text="Report incident" size={15} Icon={<AddRoundedIcon/>} ></Btn>
                <TabPanel value="1"><Table incidents={incidents} info="open"/></TabPanel>
                <TabPanel value="2"><Table incidents={incidents} info="waiting"/></TabPanel>
                <TabPanel value="3"><Table incidents={incidents} info="closed"/></TabPanel>
              </TabContext>
            </Box>  
        </div>
    </div>
  )
}

export default Incident;