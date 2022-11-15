import { useState } from 'react';
import { Box, Tab } from '@mui/material';
import { TabPanel, TabContext, TabList } from '@mui/lab';
import styles from  "./Users.module.css"
import Table from '../../components/Tabel/TableUsers';
import Sidebar from '../../components/Sidebar/Sidebar';
import Header from '../../components/Header/Header';
import {useViewport} from '../../components/hooks/viewport'
import { Users } from '../../../types';
import Cards from '../../components/Cards/Cards';

let user:Users[] = [
  {id:0,FirstName:"DEMO00",LastName:"DEMODEV00",Email:"youssef.j@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:1,FirstName:"DEMO01",LastName:"DEMODEV01",Email:"ri.j@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:2,FirstName:"DEMO02",LastName:"DEMODEV02",Email:"zaef.j@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:3,FirstName:"DEMO03",LastName:"DEMODEV03",Email:"jaeaz.p@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:4,FirstName:"DEMO04",LastName:"DEMODEV04",Email:"hdehbez@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:5,FirstName:"DEMO05",LastName:"DEMODEV05",Email:"podsq.r@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:6,FirstName:"DEMO06",LastName:"DEMODEV06",Email:"bdeehgezd@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:7,FirstName:"DEMO07",LastName:"DEMODEV07",Email:"feziuhfe.ojhds@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:8,FirstName:"DEMO08",LastName:"DEMODEV08",Email:"uehuhdgs@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:9,FirstName:"DEMO09",LastName:"DEMODEV09",Email:"cshjchjvdc@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:10,FirstName:"DEMO10",LastName:"DEMODEV10",Email:"vkjbdfhjgdfuif@hotmail.com",Position:"Student",Location:"Demo vestiging"},
  {id:11,FirstName:"DEMO11",LastName:"DEMODEV11",Email:"cdhbhudcvsyucv@hotmail.com",Position:"Student",Location:"Demo vestiging"}
]
const UserPagina = () => {
  const [value, setValue] = useState('1');
  const handleChange = (event: React.SyntheticEvent, newValue: string) => {
    setValue(newValue);
  };
  const {width} = useViewport();
  if(width>720){
    return (
      <div className={styles.container}>
        <Sidebar />
        <div className={styles.usersContainer}>
          <Header title="Users" />
          <Box sx={{ width: '100%', typography: 'body1' }}>
            <TabContext value={value}>
              <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                <TabList onChange={handleChange} aria-label="lab API tabs example">
                  <Tab label="Toegewezen" value="1" />
                  <Tab label="Toestellen zonder vestiging" value="2" />
                </TabList>
              </Box>
              <TabPanel value="1"><Table /></TabPanel>
              <TabPanel value="2"><Table /></TabPanel>
            </TabContext>
          </Box>
        </div>
      </div>
    )
  }
  else {
    return (<>
    <div className={styles.container}>
        <Sidebar />
        <div className={styles.usersContainer}>
          <Header title="Users" />
          <Box sx={{ width: '100%', typography: 'body1' }}>
            <TabContext value={value}>
              <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
                <TabList onChange={handleChange} aria-label="lab API tabs example">
                  <Tab label="Toegewezen" value="1" />
                  <Tab label="Toestellen zonder vestiging" value="2" />
                </TabList>
              </Box>
              <TabPanel value="1">{user.map((user:Users)=> <div>
              <Cards data={user}></Cards>
              </div>
               )}</TabPanel>
              <TabPanel value="2"><h1>Error 404 not found</h1></TabPanel>
            </TabContext>
          </Box>
    
    </div>
    </div>
    </>)
  }
 
}

export default UserPagina;