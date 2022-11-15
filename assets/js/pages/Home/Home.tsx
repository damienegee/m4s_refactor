import Header from "../../components/Header/Header";
import Sidebar from "../../components/Sidebar/Sidebar";
import Counter from "../../components/Widgets/Counter/Counter";
import styles from  "../page.module.css"
import homeStyles from "./Home.module.css"
const Home = () => {
  return (
    <div className={styles.container}>
        <Sidebar/>
        <div className={styles.pageContainer}>
            <Header title="My Dashboard"/>
            <div className={homeStyles.widgetsContainer}>
              <Counter type="devices"/>
              <Counter type="incidents"/>
              <Counter type="users"/>
            </div>
        </div>
    </div>
  )
}

export default Home
