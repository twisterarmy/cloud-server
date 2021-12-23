#!/usr/bin/python

import sys, MySQLdb

db = MySQLdb.connect(host="localhost",  # your host, usually localhost
                     user="root",       # your username
                     passwd="password", # your password
                     db="twister-stat") # name of the data base


cursor = db.cursor()

blocksInStep  = 1000000 # blocks processing by the one step

class MyDb:
    nextBlock = 0

process = MyDb()

cursor.execute ("SELECT COUNT(*) + 1 AS nextBlock FROM block")
row = cursor.fetchone()

process.nextBlock = row[0]

try:
    from bitcoinrpc.authproxy import AuthServiceProxy
except ImportError as exc:
    sys.stderr.write("Error: install python-bitcoinrpc (https://github.com/jgarzik/python-bitcoinrpc)\n")
    exit(-1)

serverUrl = "http://user:password@127.0.0.1:28332"
if len(sys.argv) > 1:
    serverUrl = sys.argv[1]

twister = AuthServiceProxy(serverUrl)

print "blockchain reading..."

while True:

    hash  = twister.getblockhash(process.nextBlock)
    block = twister.getblock(hash)

    blocksInStep = blocksInStep - 1

    if blocksInStep < 0:
        break

    print "add block", block["height"]
    cursor.execute("INSERT INTO block SET hash = %s, time = %s", (block["hash"], block["time"]))

    blockId = db.insert_id()

    for userName in block["usernames"]:

        print "add user", userName
        cursor.execute("INSERT INTO user SET blockId = %s, username = %s", (blockId, userName))

    if block.has_key("nextblockhash"):
        process.nextBlock = process.nextBlock + 1
    else:
        print "database is up to date..."
        break

db.commit()
db.close()

print "task completed."