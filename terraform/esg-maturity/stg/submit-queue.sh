# login and select project
IBMCLOUD_API_KEY=$IBMCLOUD_API_KEY ibmcloud login -r "$IBMCLOUD_REGION" -g "$IBMCLOUD_GROUP"
ibmcloud ce project select -n "$IBMCLOUD_PROJECT"

# delete all running jobs

# get number of lines in job list
LINES=$(ibmcloud ce jr list -j "$JOB_NAME" | wc -l)

# subtract 5 for default header lines
JOBS_RUNNING=$((LINES - 5))

echo "Number of jobs running: $JOBS_RUNNING"

## for loop to delete all jobs
i=1
while [ $i -le $JOBS_RUNNING ]; do
    JOB_RUN_NAME=$(ibmcloud ce jr list -j "$JOB_NAME" | awk 'NR == ((6+i)) {print $1}')
    ibmcloud ce jobrun delete -n "$JOB_RUN_NAME" --force
    i=$((i + 1))
done

# submit new job
ibmcloud ce jr submit -j "$JOB_NAME"