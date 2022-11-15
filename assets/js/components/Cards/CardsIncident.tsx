import { Incidentsobj } from '../../../incidenttypes';
import styles from './cards.module.css';
const CardsIncident  = ({data}:{data:Incidentsobj}) => {
    return (<>

        <div className={styles.card}>
            <div className={styles.left}>
                    <div className={styles.row}>
                        Student : 
                    </div>
                    <div className={styles.row}>
                         Serial Number : 
                    </div>
                    <div className={styles.row}>
                            Problem : 
                    </div>
                    <div className={styles.row}>
                         Status : 
                    </div>
                    <div className={styles.row}>
                         Created : 
                    </div>
                    </div>
            <div className={styles.right}>
                    <div className={styles.row}>
                        {data.Student} 
                    </div>
                    <div className={styles.row}>
                        {data.SerialNumber} 
                    </div>
                    <div className={styles.row}>
                        {data.Problem}
                    </div>
                    <div className={styles.row}>
                        {data.Status}
                    </div>
                    <div className={styles.row}>
                        {data.Created}
                    </div>
            </div>
        </div>
    </>)
}
export default CardsIncident;