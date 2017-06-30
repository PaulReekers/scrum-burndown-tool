import Graph from './Graph';
import moment from 'moment';

export default Graph.extend({

    mounted() {
    this.$http.get(this.url)
        .then(response => {
            const data = response.data;

            var start = new Date(data[0].startDate),
                startDate = new Date(data[0].startDate),
                end = new Date(data[0].endDate),
                totalDays = moment(end).format('DDDD') - moment(start).format('DDDD'),
                labels = [],
                ideal = parseInt(data[0].storyPointsTotal),
                count = 0,
                storyPoints = [],
                taskIssues = [],
                idealLine = [],
                storyPointsTotal = [];

            /**
             * Exclude the weekends to get the total working days.
             * To calculate the Ideal line.
             */
            while (startDate <= end)
            {
                if (moment(startDate).format('ddd') === 'Sat' ||
                    moment(startDate).format('ddd') === 'Sun')
                {
                    totalDays --;
                }

               var newDate = startDate.setDate(startDate.getDate() + 1);
               startDate = new Date(newDate);
            }

            /**
             * Push the labels and ideal points in an array
             */
            while (start <= end)
            {
                if (moment(start).format('ddd') !== 'Sat' &&
                    moment(start).format('ddd') !== 'Sun')
                {
                    labels.push(moment(start).format('dd Do'));

                    ideal = (
                        data[0].storyPointsTotal -
                        (data[0].storyPointsTotal / totalDays) *
                        count
                    );

                    idealLine.push(
                        Math.round(ideal + "e+2")  + "e-2"
                    );
                    count ++;
                }

                var newDate = start.setDate(start.getDate() + 1);
                start = new Date(newDate);
            }

            for (var key in data)
            {
                var weekday = data[key].sprintDay;

                if (data.hasOwnProperty(key) &&
                    moment(weekday).format('ddd') !== 'Sat' &&
                    moment(weekday).format('ddd') !== 'Sun')
                {
                    storyPoints.push(
                        data[key].storyPointsTotal - data[key].storyPointsDone
                    );

                    taskIssues.push(
                        Math.round(
                            (data[key].storyPointsTotal / data[key].tasksTotal) *
                            (data[key].tasksTotal - data[key].tasksDone)
                        )
                    );

                    storyPointsTotal.push(data[key].storyPointsTotal);
                }
            }

            this.render ({
                labels:  labels,

                datasets: [
                    {
                        label: 'Work Left',
                        data: storyPoints,
                        borderColor: 'rgba(51, 102, 204, 1)',
                        backgroundColor: 'rgba(240, 162, 235, 0.0)',
                    },
                    {
                        label: 'Progress',
                        data: taskIssues,
                        backgroundColor: 'rgba(202, 57, 18, 0.2)',
                    },
                    {
                        label: 'Ideal',
                        data: idealLine,
                        borderColor: 'rgba(249, 153, 0, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.0)',
                    },
                    {
                        label: 'Total Work',
                        data: storyPointsTotal,
                        borderColor: 'rgba(49, 150, 43, 1)',
                        backgroundColor: 'rgba(49, 150, 43, 0.0)',
                    }
                ]
            });
        });
    }
});
