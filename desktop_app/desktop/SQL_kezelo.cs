using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using MySql.Data.Common;
using System.Windows.Forms;
using System.IO;
using MySql.Data.MySqlClient;
using System.Data.SqlClient;
using System.Data;

namespace bolyGO_app
{
	static class SQLKezelo
	{

		private static string connStr = "server=localhost; user=root; password=''; port=";
		private static MySqlConnection conn = new MySqlConnection(connStr);
		private static MySqlCommand cmd;

		private static string dbname = "freas_garden";

		private static MySqlDataAdapter mda = null;
		//BindingSource bsource = new BindingSource();
		//DataSet ds = null;


		//sql non-query parancs futtatása
		static void runCommand(string cmdstr)
		{
			try
			{
				conn.Open();
				cmd = new MySqlCommand(cmdstr, conn);
				cmd.ExecuteNonQuery();
				conn.Close();
			}
			catch(Exception e)
			{
				conn.Close();
				MessageBox.Show(e.ToString(), "SQL hiba", MessageBoxButtons.OK);
			}
		}

		//adatbázis létrehozása sql fájl futtatásával
		public static void createDB(int port)
		{
			connStr += port.ToString();
			conn = new MySqlConnection(connStr);
			try
			{
				runCommand(new StreamReader($"{dbname}.sql").ReadToEnd());
				conn = new MySqlConnection($"{connStr}; database='{dbname}'");
				//visszadobja a főprogramnak az errort ami majd kezeli
			}
			catch(Exception e)
			{
				conn.Close();
				throw e;
			}
		}

	

		//Csomagjarmu frissitése
		/*public void CsomagJarmuUpdate(string csomagid, string jarmuid, bool kapcsolt)
		{
			try
			{
				conn.Open();
				if(kapcsolt)
				{
					new MySqlCommand($"REPLACE INTO csomagjarmu (`csomagid`,`jarmuid`) VALUES ('{csomagid}','{jarmuid}');", conn).ExecuteNonQuery();
				}
				else
				{
					new MySqlCommand($"DELETE FROM csomagjarmu WHERE csomagid = {csomagid} AND jarmuid = {jarmuid}", conn).ExecuteNonQuery();
				}
				conn.Close();
			}
			catch(Exception e)
			{
				conn.Close();
				MessageBox.Show($"Hiba az adatok feltöltésénél.\n{e.Message}", "SQL hiba", MessageBoxButtons.OK);
			}
		}*/

		//Táblázat módosításainak visszamentése az adatbázisba
		/*public static void updateDB(DataGridView dgv, string table)
		{
			bsource.ResetBindings(true);
			DataTable dt = ds.Tables[table];
			mda.Update(dt);
			dgv.BindingContext[dt].EndCurrentEdit();
		}*/
	}
}

