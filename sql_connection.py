import mysql.connector

__cnx = None
def get_sql_connection(__cnx=None):
    if __cnx is None:
        __cnx = mysql.connector.connect(user='root', password='root',host='localhost', database='gs')

    return __cnx


def get_sql_connection_cursor():
    return None