import { useState } from 'react';
import { Box, Tab } from '@mui/material';
import { TabPanel, TabContext, TabList } from '@mui/lab';
import styles from "../page.module.css"
import AddRoundedIcon from '@mui/icons-material/AddRounded';
import Btn from '../../components/Button/Btn';
import Table from '../../components/Tabel/TableUsers';
import Sidebar from '../../components/Sidebar/Sidebar';
import Header from '../../components/Header/Header';
import { Link } from 'react-router-dom';
interface User
{
    id:number
    FirstName:string,
    LastName:string,
    Email:string,
    Position:string,
    Location:string
}
let users:User[] = [
  {id:0,FirstName:"DEMO00",LastName:"DEMODEV00",Email:"DEMO00",Position:"Student",Location:"Demo vestiging"},
  {id:1,FirstName:"DEMO01",LastName:"DEMODEV01",Email:"DEMO01",Position:"Student",Location:"Demo vestiging"},
  {id:2,FirstName:"DEMO02",LastName:"DEMODEV02",Email:"DEMO02",Position:"Student",Location:""},
  {id:3,FirstName:"DEMO03",LastName:"DEMODEV03",Email:"DEMO03",Position:"Student",Location:""},
  {id:4,FirstName:"DEMO04",LastName:"DEMODEV04",Email:"DEMO04",Position:"Student",Location:"Demo vestiging"},
  {id:5,FirstName:"DEMO05",LastName:"DEMODEV05",Email:"DEMO05",Position:"Student",Location:"Demo vestiging"},
  {id:6,FirstName:"DEMO06",LastName:"DEMODEV06",Email:"DEMO06",Position:"Student",Location:"Demo vestiging"},
  {id:7,FirstName:"DEMO07",LastName:"DEMODEV07",Email:"DEMO07",Position:"Student",Location:"Demo vestiging"},
  {id:8,FirstName:"DEMO08",LastName:"DEMODEV08",Email:"DEMO08",Position:"Student",Location:""},
  {id:9,FirstName:"DEMO09",LastName:"DEMODEV09",Email:"DEMO09",Position:"Student",Location:"Demo vestiging"},
  {id:10,FirstName:"DEMO10",LastName:"DEMODEV10",Email:"DEMO10",Position:"Student",Location:"Demo vestiging"},
  {id:11,FirstName:"DEMO11",LastName:"DEMODEV11",Email:"DEMO11",Position:"Student",Location:"Demo vestiging"}
]
const Users = () => {
  const [value, setValue] = useState('1');
  const handleChange = (event: React.SyntheticEvent, newValue: string) => {
    setValue(newValue);
  };
  return (
    <div className={styles.container}>
      <Sidebar />
      <div className={styles.pageContainer}>
        <Header title="Users" />
        <Box sx={{ width: '100%', typography: 'body1' }}>
          <TabContext value={value}>
            <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
              <TabList onChange={handleChange} aria-label="lab API tabs example">
                <Tab label="Toegewezen" value="1" />
                <Tab label="Users zonder vestiging" value="2" />
              </TabList>
            </Box>
            <Btn text="Voeg gebruiker toe" size={15} Icon={<AddRoundedIcon />} ></Btn>
            <TabPanel value="1"><Table users={users} info="toegewezen"/></TabPanel>
            <TabPanel value="2"><Table users={users} info="noLocation"/></TabPanel>
          </TabContext>
        </Box>
      </div>
    </div>
  )
}

export default Users;