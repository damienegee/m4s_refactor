import { useState } from 'react';
import { Box,Tab } from '@mui/material';
import {TabPanel,TabContext,TabList} from '@mui/lab/';
import "./Inventaris.css"
import Header from "../../components/Header/Header";
import Sidebar from "../../components/Sidebar/Sidebar";
import Table from '../../components/TableInventaris/Table';


const Inventaris = () => {
  const [value, setValue] = useState('1');
  const handleChange = (event: React.SyntheticEvent, newValue: string) => {
    setValue(newValue);
  };
  return (
    <div className="container">
        <Sidebar/>
        <div className="inventarisContainer">
            <Header title="Inventaris"/>
            <Box sx={{ width: '100%', typography: 'body1' }}>
              <TabContext value={value}>
                <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                  <TabList onChange={handleChange} aria-label="TableInfo">
                    <Tab label="Toegewezen" value="1" />
                    <Tab label="Toestel zonder gebruiker" value="2" />
                    <Tab label="Toestellen zonder vestiging" value="3" />
                    <Tab label="Niet BYOD toestellen" value="4" />
                  </TabList>
                </Box>
                <TabPanel value="1"><Table/></TabPanel>
                <TabPanel value="2">Item Two</TabPanel>
                <TabPanel value="3">Item Three</TabPanel>
                <TabPanel value="4">Item Four</TabPanel>
              </TabContext>
            </Box>  
        </div>
    </div>
  )
}

export default Inventaris;